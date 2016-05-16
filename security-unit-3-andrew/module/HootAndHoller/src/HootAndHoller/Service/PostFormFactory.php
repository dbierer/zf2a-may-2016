<?php

namespace HootAndHoller\Service;

use HootAndHoller\Form\PostForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\Adapter;

class PostFormFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $filter = $services->get('hoot-and-holler-post-filter');
        $form = new PostForm();
        $form->prepareElements();
        $filter->prepareFilters();
        $form->setInputFilter($filter);
        return $form;
    }
}
