<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . '" ' : '';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';
$link_attr = '';
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
$flink = $item->flink;
$flink = JFilterOutput::ampReplace(htmlspecialchars($flink));

switch ($item->browserNav) :
    default:
    case 0:
        ?><a <?php echo $class; ?>href="<?php echo $flink; ?>" <?php echo $title; ?> <?php echo $link_attr; ?>><?php echo $linktype . ((isset($subtitle) && strlen($subtitle) > 0) ? $subtitle : ''); ?></a><?php
        break;
    case 1:
        // _blank
        ?><a <?php echo $class; ?>href="<?php echo $flink; ?>" target="_blank" <?php echo $title; ?> <?php echo $link_attr; ?>><?php echo $linktype . $subtitle . '111'; ?></a><?php
        break;
    case 2:
        // Use JavaScript "window.open"
        $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $params->get('window_open');
        ?><a <?php echo $class; ?>href="<?php echo $flink; ?>" onclick="window.open(this.href, 'targetWindow', '<?php echo $options; ?>');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
        break;
endswitch;
