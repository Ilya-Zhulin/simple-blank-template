<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Joomla\CMS\Factory;

jimport('joomla.form.formfield');

class JFormFieldJscript extends JFormField
{

	protected $type = 'Jscript';

	protected function getInput()
	{
		$doc = Factory::getApplication()->getDocument();
		$doc->addScript('../templates/simple_blank/includes/Field/jscript.min.js');
		$doc->addScript('../templates/simple_blank/includes/Field/position-nav.min.js');

		return '';
	}

	public function renderField($options = array())
	{
		return '';
	}

}
