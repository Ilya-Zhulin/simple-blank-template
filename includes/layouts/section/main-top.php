<?php

/*
 * File main-top.php is programmed
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
if ($config->getDoc()->countModules('sb-main-top') || (isset($sections['sb-main-top']) && $sections['sb-main-top']['isExist'] > 0))
{
	echo $config->_buildPosition('sb-main-top', $sections);
}
