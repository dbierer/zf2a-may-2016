<?php

namespace HootAndHoller\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\Filter;

class LoginFilter extends InputFilter
{
    public function prepareFilters()
    {
        $username = new Input('username');
        $username->getFilterChain()
                 ->attachByName('StripTags')
                 ->attachByName('StringTrim');
        /** -- Task
         * This Input element will need to have a standard
         * alphanumeric validator instead of email address
         */
        $username->getValidatorChain()
                //->addValidator(new Validator\EmailAddress());
                ->addValidator(new Validator\Regex(array('pattern' => '/^\w+$/')));
        $username->setRequired(TRUE);

        $password = new Input('password');
        $password->getFilterChain()
                 ->attachByName('StripTags')
                 ->attachByName('StringTrim');
        $password->setRequired(TRUE);

        $new = new Input('new');
        $new->getFilterChain()->attachByName('Int');
        $new->setRequired(FALSE);

        $realName = new Input('realName');
        $realName->getFilterChain()->attachByName('StripTags');
        $realName->setAllowEmpty(TRUE);
        $realName->getValidatorChain()
                 ->addByName('StringLength', array(0, 128));

        $this->add($username)
             ->add($password)
             ->add($new)
             ->add($realName);
    }
}
