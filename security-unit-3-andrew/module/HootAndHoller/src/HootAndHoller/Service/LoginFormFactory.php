<?php

namespace HootAndHoller\Service;

use HootAndHoller\Form\LoginForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class LoginFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $filter = $services->get('hoot-and-holler-login-filter');
        $form = new LoginForm();
        $form->prepareElements();
        $filter->prepareFilters();
        $form->setInputFilter($filter);
        return $form;
    }
}
