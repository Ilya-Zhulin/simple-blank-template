<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jimport('joomla.form.formfield');

class JFormFieldSbcss extends JFormField {

	protected $type = 'Sbcss';

	protected function getInput() {
		$doc = JFactory::getDocument();
		$doc->addStyleSheet('../templates/simple_blank/includes/fields/css/sbcss.min.css');
	}

}
