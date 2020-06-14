<?php

/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 */

jimport('joomla.application.module.helper');
$widgets	 = JModuleHelper::getModules($position);
$responsive	 = '';
$count		 = count($widgets);
if (($count < 1) || ($count > 6 && count < 10)) {
	echo '<div class="uk-width-1-1">Error: Only up to 6 widgets (or 10 exactly) are supported in this layout. If you need more add your own layout.</div>';
	return;
}
switch ($position) {
	case 'top-a':
		$responsive	 = $layouts_top_a->responsive;
		break;
	case 'top-b':
		$responsive	 = $layouts_top_b->responsive;
		break;
	case 'top-c':
		$responsive	 = $layouts_top_c->responsive;
		break;
	case 'bottom-a':
		$responsive	 = $layouts_bottom_a->responsive;
		break;
	case 'bottom-b':
		$responsive	 = $layouts_bottom_b->responsive;
		break;
	case 'bottom-c':
		$responsive	 = $layouts_bottom_c->responsive;
		break;
}
foreach ($widgets as $index => $widget) {
	$class = ($responsive) ? "uk-width-1-1 uk-width-" . $responsive . "-1-$count" : " uk-width-1-1 uk-width-1-$count";

	echo '<div class="' . $class . '">' . JModuleHelper::renderModule($widget) . '</div>';
}
$position = '';
