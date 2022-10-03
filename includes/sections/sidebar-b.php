<?php

/*
 * File sidebar-b.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
foreach ($sections['sb-sidebar-b'] as $sb_sidebar_b_position) {
	if (is_array($sb_sidebar_b_position) && strtolower($sb_sidebar_b_position['pos-name']) !== 'sb-sidebar-b' && $this->countModules($sb_sidebar_b_position['pos-name'])) {
		echo _buildPosition($this, 'sb-sidebar-b', $tplparams, $sections);
	} else {
		echo '<jdoc:include type="modules" name="' . $sb_sidebar_a_position['pos-name'] . '" style="html5" />';
	}
}