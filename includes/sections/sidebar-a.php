<?php

/*
 * File sidebar-a.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
foreach ($sections['sb-sidebar-a'] as $sb_sidebar_a_position) {
	if (is_array($sb_sidebar_a_position) && strtolower($sb_sidebar_a_position['pos-name']) !== 'sidebar-a' && $this->countModules($sb_sidebar_a_position['pos-name'])) {
		echo _buildPosition($this, 'sb-sidebar-a', $tplparams, $sections);
	}
}