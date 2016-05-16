<?php
namespace Admin\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;

class UserDeleteFilter
{
	public function prepareFilters($userList)
	{
		$inArray = new InArray();
		$inArray->setHaystack($userList);
		$users = new Input('users');
		$users->getValidatorChain()->addValidator($inArray);
		$users->setRequired(FALSE);
		$users->allowEmpty(TRUE);
		
		$inputFilter = new InputFilter();
		$inputFilter->add($users);
		
		return $inputFilter;
	}
}