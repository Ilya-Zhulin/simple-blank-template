<?php

/*
 * File main-sidebar-b.php is programmed
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
foreach ($sections['sb-main-sidebar-b'] as $sb_sidebar_b_position)
{
	if (is_array($sb_sidebar_b_position) && strtolower($sb_sidebar_b_position['pos-name']) !== 'main-sidebar-b' && $config->getDoc()->countModules($sb_sidebar_b_position['pos-name']))
	{
		echo $config->_buildPosition('sb-main-sidebar-b', $sections);
	}
}
