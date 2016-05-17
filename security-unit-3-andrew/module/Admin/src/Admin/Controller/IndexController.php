<?php
/**
 * Performs admin functions: add / edit / delete users
 * @author db
 *
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form;
use Admin\Model\UsersTable;
use Admin\Service\UserService;

class IndexController extends AbstractActionController
{

	/**
	 * Represents a Zend\Db\Tablegateway\Tablegateway model
	 * @var Admin\Model\UsersTable
	 */
	protected $usersTable;
	protected $userService;
	protected $serviceManager;
		
	/**
	 * Lists users
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction()
    {
    	$users = $this->userService->getAllUsers();
		return new ViewModel(array('users' => $users));
    }
    
    /**
     * Lists users and allows admin to delete
     * @return \Zend\View\Model\ViewModel
     */
	public function deleteAction()
    {
    	$message = '';
    	$data = '';
    	$count = 0;
    	$request = $this->getRequest();
    	$this->userService = new UserService($this->usersTable);
    	$userList = $this->userService->getAllUsersForForm();
    	// Build form elements
    	$form = $this->serviceManager->get('admin-user-delete-form');
    	$form->prepareElements($userList);
    	$filter = $this->serviceManager->get('admin-user-delete-filter');
    	$inputFilter = $filter->prepareFilters($userList);
    	$form->setInputFilter($inputFilter);
    	if ($request->isPost()) {
    		$data = $request->getPost();
    		$form->setData($data);
    		if ($form->isValid()) {
				$data = $form->getData();
    			foreach ($data['users'] as $email) {
   					$count += $this->userService->deleteUserByEmail($email);
    			}
    			$message .= $count . ' user(s) deleted!';
    		}
    	}
    	return new ViewModel(array('form' => $form, 'data' => $data, 'message' => $message));
    }
    
    public function addAction()
    {
    	$message = '';
    	$data = '';
    	$request = $this->getRequest();
    	$form = $this->serviceManager->get('admin-user-info-form');
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    			$this->userService = new UserService($this->usersTable);
    			$data = $form->getData();
    			$message = $this->userService->addUser($data);
    		} else {
    			$data = $request->getPost();
    		}
    	}
        return new ViewModel(array('form' => $form, 'data' => $data, 'message' => $message));
    }

    /**
     * Called from IndexControllerFactory
     * @param \Admin\Model\UsersTable $usersTable
     * @return \Admin\Controller\IndexController
     */
	public function setUsersTable(UsersTable $usersTable)
	{
		$this->usersTable = $usersTable;
		return $this;
	}
    /**
     * Called from IndexControllerFactory
     * @param \Admin\Service\UserService $this->userService
     * @return \Admin\Controller\IndexController
     */
	public function setUserService(UserService $userService)
	{
		$this->userService = $userService;
		return $this;
	}
	/**
	 * Called from IndexControllerFactory
	 * @param ServiceManager
	 * @return \Admin\Controller\IndexController
	 */
	public function setServiceManager($serviceManager)
	{
		$this->serviceManager= $serviceManager;
		return $this;
	}
}

