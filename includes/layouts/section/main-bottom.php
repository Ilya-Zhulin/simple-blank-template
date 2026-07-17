<?php

/*
 * File main-bottom.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
$displayData = $displayData ?? [];
$config      = $displayData['config'] ?? null;
$template    = $displayData['template'] ?? null;

if ($config)
{
	extract($config->data);
}
if ($config->getDoc()->countModules('sb-main-bottom') || (isset($sections['sb-main-bottom']) && $sections['sb-main-bottom']['isExist'] > 0))
{
	echo $config->_buildPosition('sb-main-bottom', $sections);
}
