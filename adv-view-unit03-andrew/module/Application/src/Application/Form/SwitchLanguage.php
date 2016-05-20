<?php
namespace Application\Form;
use Zend\Form\Form;
use Zend\Form\Element\Select;

class SwitchLanguage extends Form
{
	public function __construct(Array $languages)
	{
		parent::__construct();
		$language = new Select('language');
		$language->setValueOptions($languages);
		$this->add($language);
		$this->setAttributes(array('method' => 'get', 'onChange' => 'submit();'));
	}
}