<?php
namespace HootAndHoller\Form;
use Zend\Form\Form;
use Zend\Form\Element;

class HootAndHollerForm extends Form
{

	public function prepareElements($users)
	{
		$text = new Element\Text('text');
		$text->setLabel('Text')
			 ->setAttributes(array('maxlength' => 128, 'class' => 'text'));

		$recipient = new Element\Select('recipient');
		$recipient->setLabel('Recipient')
				  ->setOptions(array('options' => $users));

		$type = new Element\Radio('type');
		$type->setLabel('Type')
			 ->setAttribute('class', 'radio')
			 ->setOptions(array('options' => array('T' => 'Hoot', 'R' => 'Holler')));
		
		$submit = new Element\Submit('submit');
		$submit->setAttribute('value', 'Send');

		$this->add($text)
			 ->add($recipient)
			 ->add($type)
			 ->add($submit);
	}
	
}
