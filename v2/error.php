<?php

/*
 * Simple Blank Template
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2021
 *
 * Don't change this file!
 * Error templates are in /includes/ folder
 */


defined('_JEXEC') or die('Restricted access');
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}

$app			 = JFactory::getApplication('site');
$templateparam	 = $app->getTemplate($this->template);
$theme			 = $templateparam->params->get('theme_select', '');
if ($this->error->getCode() == '404') {
	header("HTTP/1.0 404 Not Found");
	$url = JPATH_THEMES . "/" . $this->template . "/themes/$theme/error404.php";
	if (strlen($theme) == 0 || !JFile::exists($url)) {
		$url = JPATH_THEMES . "/" . $this->template . "/includes/error404.php";
	}
} else {
	header($this->error->getCode() . " - " . htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'));
	$url = JPATH_THEMES . "/" . $this->template . "/themes/$theme/error.php";
	if (strlen($theme) == 0 || !JFile::exists($url)) {
		$url = JPATH_THEMES . "/" . $this->template . "/includes/error.php";
	}
}
include_once $url;
