<?php

/*
 * File top.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
// Top sections
foreach ($sb_top_sections_array as $sb_top_sections_item) {
	if ($this->countModules($sb_top_sections_item) || (isset($sections[$sb_top_sections_item]) && $sections[$sb_top_sections_item]['isExist'] > 0)) {
		echo _buildPosition($this, $sb_top_sections_item, $tplparams, $sections);
	}
}