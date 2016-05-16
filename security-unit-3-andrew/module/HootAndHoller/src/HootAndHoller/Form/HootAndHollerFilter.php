<?php
namespace HootAndHoller\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;
use Zend\Filter;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;
use Zend\Validator\Regex;
class HootAndHollerFilter
{
	public function prepareFilters()
	{
		$text = new Input('text');
		$text->getValidatorChain()->addValidator(new Validator\StringLength(array(1,128)));
		$text->getFilterChain()->attachByName('StripTags');
		$email = new Input('recipient');
		$email->getFilterChain()->attachByName('StripTags');
		$email->getValidatorChain()->addValidator(new Validator\EmailAddress());
		$email->setAllowEmpty(TRUE);
		$email->setRequired(FALSE);
		$type = new Input('type');
		$type->setValue('H');
		$type->setRequired(FALSE);
		$type->getFilterChain()
			 ->attachByName('StringTrim')
			 ->attachByName('StringToUpper');
		$type->getValidatorChain()
			 ->addValidator(new Regex('/^T|R$/'));
		$inputFilter = new InputFilter();
		$inputFilter->add($text)
					->add($email)
					->add($type);		
		return $inputFilter;
	}
}
