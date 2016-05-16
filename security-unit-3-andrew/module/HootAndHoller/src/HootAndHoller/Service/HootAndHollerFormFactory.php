<?php

namespace HootAndHoller\Service;

use HootAndHoller\Form\HootAndHollerForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/** -- Task: change reference to Zend\Ldap\Ldap */
//use Zend\Db\Adapter\Adapter;
use Zend\Ldap\Ldap;

class HootAndHollerFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
    	/** -- Task: remove reference to users table */
    	//$usersTable = $services->get('users-table');
    	/** -- Task:
		 *           Retrieve the LDAP config
		 *           Build an LDAP object using the config for 'server1'
		 *           Set search options as follows:
		    			'filter' => '(objectclass=*)',
		    			'baseDn' => $ldapConfig['server1']['baseDn'],
		    			'scope'	 => Ldap::SEARCH_SCOPE_ONE,
    	*   		Use the 'searchEntries()' method to find all entries for this baseDn
    	*   		Build the $users array using the appropriate LDAP attributes
    	 */
	   	/*$users = array_merge(array('' => 'Choose'), $usersTable->getSelectUsers());
    	$filter = $services->get('hoot-and-holler-post-filter');
        $form = new HootAndHollerForm();
        $form->prepareElements($users);
        $form->setInputFilter($filter->prepareFilters());*/
        
        $ldapConfig = $services->get('ldap-config');
        $ldap = new Ldap($ldapConfig['server1']);
        $searchOptions = array(
            'filter' => '(objectclass=*)',
            'baseDn' => $ldapConfig['server1']['baseDn'],
            'scope'	 => Ldap::SEARCH_SCOPE_ONE,
        );
        $result = $ldap->searchEntries($searchOptions);
        $users = array('' => 'Choose');
        foreach ($result as $item) {
            $users[$item['mail'][0]] = ucfirst($item['cn'][0]);
        }
        $filter = $services->get('hoot-and-holler-post-filter');
        $form = new HootAndHollerForm();
        $form->prepareElements($users);
        $form->setInputFilter($filter->prepareFilters());
        
        return $form;
    }
}
