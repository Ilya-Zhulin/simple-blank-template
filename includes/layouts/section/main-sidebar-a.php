<?php

/*
 * File main-sidebar-a.php is programmed
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
foreach ($sections['sb-main-sidebar-a'] as $sb_main_sidebar_a_position)
{
	if (is_array($sb_main_sidebar_a_position) && strtolower($sb_main_sidebar_a_position['pos-name']) !== 'main-sidebar-a' && $config->getDoc()->countModules($sb_main_sidebar_a_position['pos-name']))
	{
		echo $config->_buildPosition($this, 'sb-main-sidebar-a', $tplparams, $sections);
	}
}
