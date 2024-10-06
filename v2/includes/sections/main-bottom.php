<?php

/*
 * File main-bottom.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
if ($this->countModules('sb-main-bottom') || (isset($sections['sb-main-bottom']) && $sections['sb-main-bottom']['isExist'] > 0)) {
	echo _buildPosition($this, 'sb-main-bottom', $tplparams, $sections);
}