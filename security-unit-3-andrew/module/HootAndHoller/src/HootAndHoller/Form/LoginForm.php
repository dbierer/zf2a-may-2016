<?php

namespace HootAndHoller\Form;

use Zend\Form\Form as ZendForm;
use Zend\Form\Element;

class LoginForm extends ZendForm
{
    public function prepareElements()
    {
        /** -- Task
         * This form element will need to be modified
         * to accept standard text
         */
        /*$this->add(array(
                'name' => 'username',
                'options' => array(
                        'label' 	=> 'Username',
                ),
                'attributes' 	=> array(
                        'type'  	=> 'email',
                        'maxlength' => 64,
                        'title' 	=> 'Enter email address as username'
                ),
        ));*/
        $this->add(array(
            'name' => 'username',
            'options' => array(
                'label' 	=> 'Username',
            ),
            'attributes' 	=> array(
                'type'  	=> 'text',
                'maxlength' => 64,
                'title' 	=> 'Enter base LDAP username'
            ),
        ));
        $this->add(array(
                'name' => 'password',
                'options' => array(
                        'label' 	=> 'Password',
                ),
                'attributes' 	=> array(
                        'type'  	=> 'password',
                        'maxlength' => 64,
                ),
        ));
        $new = new Element\Checkbox('new');
        $new->setLabel('New Account')
            ->setUseHiddenElement(TRUE)
            ->setCheckedValue('1')
            ->setUncheckedValue('0');
        $this->add($new);
        $this->add(array(
                'name' => 'realName',
                'options' => array(
                        'label' 	=> 'Real Name',
                ),
                'attributes' 	=> array(
                        'type'  	=> 'text',
                        'maxlength' => 64,
                        'title'		=> 'New accounts only!',
                ),
        ));
        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type'  => 'submit',
                        'value' => 'Login',
                ),
        ));
    }
}
