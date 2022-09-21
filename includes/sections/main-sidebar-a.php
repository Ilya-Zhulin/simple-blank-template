<?php

/*
 * File main-sidebar-a.php is programmed
 * specially for Shop by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
foreach ($sections['sb-main-sidebar-a'] as $sb_main_sidebar_a_position) {
	if (is_array($sb_main_sidebar_a_position) && strtolower($sb_main_sidebar_a_position['pos-name']) !== 'main-sidebar-a' && $this->countModules($sb_main_sidebar_a_position['pos-name'])) {
		echo _buildPosition($this, 'sb-main-sidebar-a', $tplparams, $sections);
	}
}