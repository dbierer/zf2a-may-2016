<?php

namespace HootAndHoller\Controller;

use HootAndHoller\Model\MessagesTable;
use HootAndHoller\Service\DatabaseService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Sql;
use Zend\Authentication\AuthenticationService;

class IndexController extends AbstractActionController
{
    protected $messagesTableModel = NULL;
    
    public function setMessagesTable(MessagesTable $messagesTable)
    {
    	$this->messagesTableModel = $messagesTable;
    }
    
    public function indexAction()
    {
    	$message = $this->params()->fromQuery('message');
    	$rows = $this->messagesTableModel->fetchHollers();
    	return new ViewModel(array('messages' => $rows, 'message' => $message, 'greeting' => 'TEST'));
    }

    public function hootAction()
    {
        /** -- Task: get the authentication service from the service manager */
        //$authService = new AuthenticationService();
        //if ($authService->hasIdentity()) {
        	/*-- Task: as with the layout, retrieve the LDAP object from storage,
        	 *         use "getBoundUser()" to retrieve the full LDAP name,
        	 *         and then use "getEntry()" to get the full details.
        	 *         Look for the "mail" LDAP attribute for the email address.
        	 */
        //	$me = $authService->getIdentity()->email;
        //} else {
        //	$me = 'guest@zend.com';
        //}
        
        $authService = $this->getServiceLocator()->get('ldap-auth-service');
        if ($authService->hasIdentity()) {
            $ldap = $authService->getIdentity();
            $bind = $ldap->getBoundUser();
            $fullEntry = $ldap->getEntry($bind);
            $me = $fullEntry['mail'][0];
        } else {
            $me = 'guest@zend.com';
        }
        
        $rows = $this->messagesTableModel->fetchHoots($me);
        return new ViewModel(array('messages' => $rows));
    }

}