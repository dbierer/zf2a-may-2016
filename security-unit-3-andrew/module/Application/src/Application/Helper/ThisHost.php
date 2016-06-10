<?php
namespace Application\Helper;

use Zend\Form\View\Helper\AbstractHelper;

class ThisHost extends AbstractHelper
{
    public function render($http = 'http')
    {
        return $http . '//' . strip_tags($_SERVER['HTTP_HOST']) . '/';
    }
    public function __invoke($http = 'http')
    {
        return $this->render($http);
    }
}
