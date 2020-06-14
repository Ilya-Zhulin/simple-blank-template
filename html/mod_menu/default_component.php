<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
include_once JPATH_ROOT . '/jbdump/init.php';
// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . ' ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';
$link_attr = '';
$subtitle = '';
$subtitle_class = '';

if (preg_match("!\*subtitle\*(.*?)\*/subtitle\*!si", $item->note, $matches) > 0) {
    $subtitle = '<div class="uk-navbar-subtitle">' . $matches[1] . '</div>';
    $class = ($class !== '') ? $class . '' : '';
}

if ($item->menu_image) {
    $item->params->get('menu_text', 1) ?
                    $linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
                    $linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
} else {
    $linktype = $item->title;
}
if (preg_match("!\*replace\*(.*?)\*/replace\*!si", $item->note, $matches) > 0) {
//	JBDump($item);
    $linktype = '';
    $link_attr = ' uk-icon="' . $matches[1] . '"';
}
$class = ($class !== '') ? $class . '"' : '';
switch ($item->browserNav) {
    default:
    case 0:
        ?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" <?php echo $title; ?> <?php echo $link_attr; ?>><?php echo $linktype . $subtitle; ?></a><?php
        break;
    case 1:
        // _blank
        ?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?> <?php echo $link_attr; ?>><?php echo $linktype . $subtitle; ?></a><?php
        break;
    case 2:
        // Use JavaScript "window.open"
        ?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" onclick="window.open(this.href, 'targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;"  <?php echo $link_attr; ?> <?php echo $title; ?>><?php echo $linktype . $subtitle; ?></a>
        <?php
        break;
}
