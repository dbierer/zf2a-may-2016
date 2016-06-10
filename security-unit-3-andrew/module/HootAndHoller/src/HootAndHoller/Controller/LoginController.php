<?php

namespace HootAndHoller\Controller;

use HootAndHoller\Model\UsersTable;
use HootAndHoller\Form\LoginForm;
use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

/** -- Task: remove all Zend\Authentication references: use service manager instead */
/* Original code is removed */
/*
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as AuthStorage;
*/

use Zend\Session\Container;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;
use Zend\Ldap\Ldap;
use Zend\Ldap\Dn;
use Zend\Ldap\Attribute;
use Zend\Ldap\Filter;

class LoginController extends AbstractActionController
{
    /** -- Task: Remove references to "usersTable" : no longer needed! */
    /* Original code is removed */
    //protected $usersTable;

    protected $sessionContainer;
    protected $maxInvalidAttempts = 3;
    protected $loginForm;

    /** -- Define properties for authAdapter and authService */
    protected $authService;
    protected $authAdapter;

    public function indexAction()
    {
        $message = '';
        $data = '';
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->loginForm->setData($request->getPost());
            $valid = $this->loginForm->isValid();
            $data = $this->loginForm->getData();
            if (!$valid) {
                $this->logInvalidAttempt($data['username']);
                if ($this->setInvalidLoginCounter() > $this->maxInvalidAttempts) {
                    $this->setInvalidLoginCounter(TRUE);	// reset counter
                    return $this->redirect()->toRoute('login-invalid');
                }
            } else {
                $data = $this->loginForm->getData();
                if ($data['new'] == 1) {
                    $message .= $this->addUser($data);
                } else {

                    /** -- Task: no longer needed: these will be injected in the controller factory */
                    /* Original code is removed */
                    //$authService = $this->getAuthService();
                    //$authAdapter = $authService->getAdapter();

                    /** -- Task: retrieve the LDAP config from the service manager
                     * 			 rewrite the username in LDAP style, i.e. cn=xxx,ou=zf2widder,etc.
                     * 			 overwrite the config username and password
                     * 			 set the new configuration back into the auth adapter
                     *           set the identity to the revised username
                     */
                    /* Original code is removed */
                    //$authAdapter->setIdentity($data['username']);

                    $ldapConfig = $this->getServiceLocator()->get('ldap-config');
                    $username = 'cn=' . $data['username'] . ',' . $ldapConfig['server1']['baseDn'];
                    $ldapConfig['server1']['username'] = $username;
                    $ldapConfig['server1']['password'] = $data['password'];
                    $this->authAdapter->setOptions($ldapConfig);
                    $this->authAdapter->setIdentity($username);

                    /** -- Task: No need for "md5()" as normally the LDAP connection is secured via SSL or TLS
                     *           Also, internally, passwords are stored in LDAP using Blowfish or another secure mechanism */
                    /* Original code is removed */
                    //$authAdapter->setCredential(md5($data['password']));
                    //$attempt = $authAdapter->authenticate();

                    $this->authAdapter->setCredential($data['password']);
                    $attempt = $this->authAdapter->authenticate();

                    if ($attempt->isValid()) {

                        /** -- Task: You need to retrieve an LDAP object, not a "row" object */
                        /* Original code is removed */
                        //$userRow = $authAdapter->getResultRowObject();
                        //$authStorage = $authService->getStorage();

                        $userLdap = $this->authAdapter->getLdap();
                        $dn = Dn::factory($username);

                        $result = $userLdap->getEntry($dn);

                        if ($result['departmentnumber'][0] == '0') {
                            $message = 'You have received an email.  Please confirm your email address.';
                            return new ViewModel(array('loginForm' => $this->loginForm, 'message' => $message));
                        }

                        $authStorage = $this->authService->getStorage();

                        $authStorage->write($userLdap);
                        $this->sessionContainer->name = $username;
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->logInvalidAttempt($attempt->getIdentity());
                        if ($this->setInvalidLoginCounter() > $this->maxInvalidAttempts) {
                            $this->setInvalidLoginCounter(TRUE);	// reset counter
                            return $this->redirect()->toRoute('login-invalid');
                        } else {
                            $message = 'Unsuccessful Login';
                            $message .= '<pre>';
                            $message .= implode('<br />', $attempt->getMessages());
                            $message .= '</pre>';
                        }
                    }
                }
            }
        }
        return new ViewModel(array('loginForm' => $this->loginForm, 'message' => $message));
    }

    public function invalidAction()
    {
        return new ViewModel();
    }

    /** -- Task: OPTIONAL -- rewrite as per lab guide */
    public function confirmAction()
    {
        /* Original code is removed */
        /*
        $request = $this->getRequest();
        $email = $this->params()->fromQuery('email');
        $hash = $this->params()->fromQuery('hash');

        $result = $this->usersTable->getUserByEmailAddress($email);
        if ($result) {
            $data = $result[0];
            if ($data['preferences'] == $hash) {
                $data['status'] = 1;
                $data['preferences'] = '';
                $this->usersTable->save($data);
            }
        }
        */

        // For tests:
        //$data['username'] = strtolower('Doug');
        //$email = 'doug@unlikelysource.com';

        $request = $this->getRequest();
        $lastname = urldecode($this->params()->fromRoute('lastname'));
        $hash = $this->params()->fromRoute('hash');

        $ldapConfig = $this->getServiceLocator()->get('ldap-config');

        $ldap = new Ldap($ldapConfig['server1']);

        $ldapFilter = Filter::equals('sn', $lastname)
                               ->addAnd(Filter::equals('description', $hash));

        $ldapResultSet = $ldap->bind(
                            $ldapConfig['server1']['username'],
                            $ldapConfig['server1']['password']
                            )
                           ->search($ldapFilter, $ldapConfig['server1']['baseDn'], Ldap::SEARCH_SCOPE_ONE);

        $ldapResult = $ldapResultSet->current();

        if (!empty($ldapResult)) {
            Attribute::setAttribute($ldapResult, 'departmentNumber', '1');
            $ldap->update($ldapResult['dn'], $ldapResult);
        }

        return $this->redirect()->toRoute('login-index');
    }

    public function logoutAction()
    {
        /** -- Task: need to reference authService property */
        /* Original code is removed */
        //$authService = $this->getAuthService();
        //$authService->clearIdentity();
        //$this->sessionContainer->offsetUnset('counter');
        //$this->sessionContainer->offsetUnset('role');

        $this->authService->clearIdentity();
        $this->sessionContainer->offsetUnset('counter');
        $this->sessionContainer->offsetUnset('role');

        return $this->redirect()->toRoute('home');
    }

    /** -- Task: this method can be removed */
    /* Original code is removed */
    /*
    protected function getAuthService()
    {
          $dbAdapter = $this->usersTable->getAdapter();
          $tableName = $this->usersTable->getTableName();
          $identityCol = 'email';
          $credentialsCol = 'password';
          $authAdapter = new AuthAdapter($dbAdapter, $tableName, $identityCol, $credentialsCol);
        $authStorage = new AuthStorage();
        $authService = new AuthenticationService($authStorage, $authAdapter);
        return $authService;
    }
    */

    protected function setInvalidLoginCounter($reset = FALSE)
    {
        if (!$this->sessionContainer->offsetExists('counter') || $reset) {
            $this->sessionContainer->counter = 0;
        }
        return $this->sessionContainer->counter++;
    }

    protected function logInvalidAttempt($username)
    {
        $logger = $this->getServiceLocator()->get('invalid-login-logger');
        $logger->alert($username);
    }

    /** -- Task: OPTIONAL -- rewrite as per lab guide */
    protected function addUser($data)
    {
        /* Original code is removed */
        /*
        // save to database
           $message = 'New User Added!';
           $hash = md5(date('Y-m-d H:i:s') . rand(1,999));
           $data['email'] = $data['username'];
           $data['preferences'] = $hash;
           $data['status'] = 0;
           unset($data['username']);
           $this->usersTable->save($data);
        */

        // For tests:
        //$data['realName'] = 'Guest Zf2widder';
        //$data['username'] = 'guest';
        //$data['lastname'] = 'zf2widder';
        //$data['email'] = 'guest@unlikelysource.com';
        //$data['departmentNumber'] = 0;
        //$data['description'] = 'another slow tourist';
        //$data['phone'] = '+1 555-111-2222';
        //$data['department'] = 'Software Development';
        //$data['language'] = 'English';
        //$data['password'] = '{MD5}' . md5('password');

        $hash = md5(date('Y-m-d H:i:s') . rand(1,999));
        $data['realName'] = trim($data['realName']);
        $realNameArray = explode(' ', trim($data['realName']));
        $data['lastname'] = strtolower(array_pop($realNameArray));
        $data['email'] = $data['username'] . '@company.com';
        $data['departmentNumber'] = 0;
        $data['description'] = $hash;
        $data['phone'] = '+0 000-000-0000';
        $data['department'] = 'New Member';
        $data['language'] = 'English';
        // @TODO Store passwords with md5 hash.
        //$data['password'] = '{MD5}' . md5($data['password']);

        $ldapConfig = $this->getServiceLocator()->get('ldap-config');
        $dn = Dn::factory('cn=' . $data['username'] . ',' . $ldapConfig['server1']['baseDn']);
        $ldap = new Ldap($ldapConfig['server1']);

        $result = $ldap->bind(
            $ldapConfig['server1']['username'],
            $ldapConfig['server1']['password']
            )
            ->getEntry($dn);

        if (empty($result)) {
            $resultAdd = $ldap->add($dn, [
                'objectClass'          => [
                    'top',
                    'organizationalPerson',
                    'inetOrgPerson',
                    'person',
                ],
                'cn'                   => $data['username'],
                'sn'                   => $data['lastname'],
                'departmentNumber'     => $data['departmentNumber'],
                'description'          => $data['description'],
                'homePhone'            => $data['phone'],
                'mail'                 => $data['email'],
                'ou'                   => $data['department'],
                'preferredLanguage'    => $data['language'],
                'uid'                  => $data['username'],
                'userPassword'         => $data['password'],]
                );

            $message = 'New User Added!';
            $eventManager = new EventManager('login');
            $eventManager->trigger('email-notification', $this, array('data' => $data));
        } else {
            $message = 'This username is already taken!';
        }

           return $message;
    }

    /** -- Task:
     * Define setters for authAdapter and authService
    */

    /* Original code is removed */
    /*
    public function setUsersTable(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
        return $this;
    }

    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    public function setSessionContainer(Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
        return $this;
    }
    */

    public function setUsersTable(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
        return $this;
    }

    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    public function setSessionContainer(Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
        return $this;
    }

    /**
     * @return the $authService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param field_type $authService
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return the $authAdapter
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }

    /**
     * @param field_type $authAdapter
     */
    public function setAuthAdapter($authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }

}
