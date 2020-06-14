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
$class = '';
$attr = '';
/*
 * Т.к. для модуля меню не требуется заголовок,
 * то класс заголовка модуля используем для списка
 * содержимого владок
 */
$headerClass = htmlspecialchars($params->get('header_class', ''));

$second_level = [];
$list_array = [];
//JBDump($list, 0);
?>
<ul class="<?php echo $class_sfx; ?>"<?php
$tag = '';
if ($params->get('tag_id') != null) {
    $tag = $params->get('tag_id') . '';
    echo ' id="' . $tag . '"';
}
?> uk-tab="animation: uk-animation-slide-top-medium, uk-animation-slide-bottom-medium">

    <?php
    foreach ($list as $i => &$item) {
        if ($item->level === '1') {
            $class = 'item-' . $item->id;

            if (($item->id == $active_id) OR ( $item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id)) {
                $class .= ' current';
            }
            $liclass = '';
            if (in_array($item->id, $path)) {
                $class = ' uk-active';
            } elseif ($item->type == 'alias') {
                $aliasToId = $item->params->get('aliasoptions');

                if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                    $class .= ' uk-active';
                } elseif (in_array($aliasToId, $path)) {
                    $class .= ' alias-parent-active';
                }
            }

            if ($item->type == 'separator') {
                $class .= ' divider';
            }

            if ($item->deeper) {
                $class .= ' deeper';
                $second_level[] = $i + 1;
            }

            if ($item->parent) {
                $class .= ' uk-parent';
            }

            if ($item->deeper) {
                $attr .= ' data-uk-dropdown';
            }

            if (!empty($class)) {
                $class = ' class="' . trim($class) . '"';
            }

            echo '<li' . $class . '>';

            // Render the menu item.
            switch ($item->type) :
                case 'separator':
                case 'url':
                case 'component':
                case 'heading':
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                    break;

                default:
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                    break;
            endswitch;

            echo '</li>';
        } elseif ($item->level === '2') {
            $second_level[$item->parent_id][] = $item;
        }
    }
    ?>
</ul>
<?php
//JBDump($second_level, 0);
?>
<ul class="uk-switcher <?php echo $headerClass;
?>"<?php
    $tag = '';
    if ($params->get('tag_id') != null) {
        $tag = $params->get('tag_id') . '';
        echo ' id="' . $tag . '-switcher"';
    }
    ?>>
        <?php
        foreach ($list as $i => $item) {
            if ($item->level === '1') {
                echo '<li>';
                if ($item->deeper && (isset($second_level[$item->id]) && count($second_level[$item->id]) > 0)) {
                    echo '<ul class="uk-nav uk-nav-default">';
                    foreach ($second_level[$item->id] as &$second_lvl_item) {
                        $class = 'item-' . $second_lvl_item->id;

                        if (($second_lvl_item->id == $active_id) OR ( $second_lvl_item->type == 'alias' AND $second_lvl_item->params->get('aliasoptions') == $active_id)) {
                            $class .= ' current';
                        }
                        $liclass = '';
                        if (in_array($second_lvl_item->id, $path)) {
                            $class = ' uk-active';
                        } elseif ($second_lvl_item->type == 'alias') {
                            $aliasToId = $second_lvl_item->params->get('aliasoptions');

                            if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                                $class .= ' uk-active';
                            } elseif (in_array($aliasToId, $path)) {
                                $class .= ' alias-parent-active';
                            }
                        }

                        if ($second_lvl_item->type == 'separator') {
                            $class .= ' divider';
                        }

                        if ($second_lvl_item->deeper) {
                            $class .= ' deeper';
                        }

                        if ($second_lvl_item->parent) {
                            $class .= ' uk-parent';
                        }

                        if ($second_lvl_item->deeper) {
                            $attr .= ' data-uk-dropdown';
                        }

                        if (!empty($class)) {
                            $class = ' class="' . trim($class) . '"';
                        }
                        echo '<li' . $class . '>';
                        // Render the menu item.
                        switch ($second_lvl_item->type):
                            case 'separator':
                            case 'url':
                            case 'component':
                            case 'heading':
                                $item = $second_lvl_item;
                                require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $second_lvl_item->type);
                                break;
                            default:
                                require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                                break;
                        endswitch;
                        echo '</li>';
                    }
                    echo '</ul>';
                }
                echo '</li>';
            }
        }
        ?>
</ul>