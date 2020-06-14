<?php

/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2016
 * http://sebloders.ru
 */

jimport('joomla.application.module.helper');
$widgets = JModuleHelper::getModules($position);
$count	 = count($widgets);
foreach ($widgets as $index => $widget) {
	$class = "uk-width-1-1";
	echo '<div class="' . $class . '">' . JModuleHelper::renderModule($widget) . '</div>';
}
$position = '';
