<?php

/*
 * File bottom.php is programmed
 * specially for Shop by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
foreach ($sb_bottom_sections_array as $sb_bottom_sections_item) {
	if ($this->countModules($sb_bottom_sections_item) || (isset($sections[$sb_bottom_sections_item]) && $sections[$sb_bottom_sections_item]['isExist'] > 0)) {
		echo _buildPosition($this, $sb_bottom_sections_item, $tplparams, $sections);
	}
}