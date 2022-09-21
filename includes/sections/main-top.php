<?php

/*
 * File main-top.php is programmed
 * specially for Shop by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
if ($this->countModules('sb-main-top') || (isset($sections['sb-main-top']) && $sections['sb-main-top']['isExist'] > 0)) {
	echo _buildPosition($this, 'sb-main-top', $tplparams, $sections);
}