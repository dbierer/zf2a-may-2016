<?php

namespace HootAndHoller\Controller;

use HootAndHoller\Model\MessagesTable;
use HootAndHoller\Model\UsersTable;
use HootAndHoller\Form\HootAndHollerForm;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;

class PostController extends AbstractActionController
{

    protected $messagesTable;
    protected $usersTable;
    protected $postForm;

    public function indexAction()
    {
        $message = 'Post a "hoot" to another user, or a "holler" to all';
        $data = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->postForm->setData($request->getPost());
            if ($this->postForm->isValid()) {
                $data = $this->postForm->getData();

                /** -- Task: get the authentication service from the service manager */
                /* Original code is removed */
                //if ($authService->hasIdentity()) {
                    /*-- Task: as with the layout, retrieve the LDAP object from storage,
                     *         use "getBoundUser()" to retrieve the full LDAP name,
                     *         and then use "getEntry()" to get the full details.
                     *         Look for the "mail" LDAP attribute for the email address.
                     */
                //	$sender = $authService->getIdentity()->email;
                //} else {
                //	$sender = 'guest@zend.com';
                //}

                $authService = new AuthenticationService();

                $authService = $this->getServiceLocator()->get('ldap-auth-service');
                if ($authService->hasIdentity()) {
                    $ldap = $authService->getIdentity();
                    $bind = $ldap->getBoundUser();
                    $fullEntry = $ldap->getEntry($bind);
                    $sender = $fullEntry['mail'][0];
                } else {
                    $sender = 'guest@zend.com';
                }

                $recipient = ($data['type'] == 'T')
                           ? $data['recipient']
                           : NULL;
                $this->messagesTable->add($sender, $data['text'], $recipient);
                $message = 'Message Sent';
            } else {
                $message = 'Back to the Drawing Board!';
            }
            $data = $this->postForm->getData();
        }
        return new ViewModel(array('form' => $this->postForm, 'message' => $message, 'data' => $data));
    }

    public function setMessagesTable(MessagesTable $messagesTable)
    {
        $this->messagesTable = $messagesTable;
        return $this;
    }
    public function setUsersTable(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
        return $this;
    }
    public function setPostForm(HootAndHollerForm $form)
    {
        $this->postForm = $form;
        return $this;
    }
}
