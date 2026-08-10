<?php

/*
 * File main.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
echo \SimpleBlank\Site\Helper\DevHelper::section('sb-main-main', __FILE__);
echo '<jdoc:include type="message" />';
echo '<jdoc:include type="component" />';
