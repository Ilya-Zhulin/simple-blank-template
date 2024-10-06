<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jimport('joomla.form.formfield');

class JFormFieldJscript extends JFormField {

	protected $type = 'Jscript';

	protected function getInput() {
		$doc = JFactory::getDocument();
		$doc->addScript('../templates/simple_blank/includes/fields/jscript.min.js');
	}

}
