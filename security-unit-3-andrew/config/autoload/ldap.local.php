<?php

use Zend\Ldap\Ldap;
use Zend\Authentication\Adapter\Ldap as AuthAdapter;

return array(
    'service_manager' => array(
        'services' => array(
            'ldap-config' => array(
                'server1' => array(
                        'host'                  => 'ldap.company.com',
                        'accountDomainName'     => 'company.com',
                        'accountDomainNameShort'=> 'company',
                        // NOTE: for Active Directory you would use Ldap::ACCT_FORM_BACKSLASH
                        'accountCanonicalForm'  => Ldap::ACCTNAME_FORM_USERNAME,
                        'username'              => 'cn=adminTwo,ou=zf2widder,dc=company,dc=com',
                        'password'              => 'password',
                        'baseDn'                => 'ou=zf2widder,dc=company,dc=com',
                        'bindRequiresDn'        => 'true',
                ),
            ),
        ),
        'invokables' => array(
            'ldap-auth-service' => 'Zend\Authentication\AuthenticationService',
        ),
        'factories' => array(
            'ldap-auth-adapter' => function ($sm) {
                return new AuthAdapter($sm->get('ldap-config'));
            },
        ),
    ),
);
