<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 * @version     Simple Blank Custom (J4/J5/J6 + UIKit + Params)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

// WebAsset Manager
$wa = $app->getDocument()->getWebAssetManager();
if (method_exists($wa->getRegistry(), 'addExtensionRegistryFile'))
{
    $wa->getRegistry()->addExtensionRegistryFile('mod_menu');
    $wa->usePreset('mod_menu.menu');
}

// --- ОБРАБОТКА ПАРАМЕТРОВ МОДУЛЯ ---

// 1. Получаем ID (если задан в настройках модуля)
$tagId       = $params->get('tag_id', '');
$idAttribute = '';
if ($tagId)
{
    $idAttribute = ' id="' . htmlspecialchars($tagId, ENT_QUOTES, 'UTF-8') . '"';
}

// 2. Собираем классы для <ul>
// Базовые классы шаблона (UIKit)
$baseClasses = ['uk-nav', 'mod-list', 'nav'];

// Класс-суффикс из настроек модуля (параметр "Класс меню")
// Joomla автоматически добавляет пробел перед ним, если он не пустой, но мы соберем массив для надежности
$moduleClass = trim($params->get('class_sfx', ''));
if ($moduleClass)
{
    $baseClasses[] = $moduleClass;
}

// Превращаем массив в строку
$classString = implode(' ', $baseClasses);

// ----------------------------------

// Логика активного элемента (для открытия родителей)
$current_item = null;
foreach ($list as $i => &$item)
{
    if ($item->id == $active_id || ($item->type === 'alias' && $item->getParams()->get('aliasoptions') == $active_id))
    {
        $current_item = $item;
    }
}
?>

<!-- Вывод списка с динамическими классами и ID -->
<ul class="<?php echo $classString; ?>"<?php echo $idAttribute; ?> uk-nav>
    <?php
    foreach ($list as $i => &$item)
    {
        $itemParams = $item->getParams();
        $class      = 'item-' . $item->id;

        if ($item->id == $default_id)
        {
            $class .= ' default';
        }

        if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id))
        {
            $class .= ' current uk-active';
        }

        if (in_array($item->id, $path))
        {
            $class .= ' uk-active';
        }
        elseif ($item->type === 'alias')
        {
            $aliasToId = $itemParams->get('aliasoptions');
            if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
            {
                $class .= ' uk-active';
            }
            elseif (in_array($aliasToId, $path))
            {
                $class .= ' alias-parent-active';
            }
        }

        if ($item->type === 'separator')
        {
            $class .= ' divider';
        }

        if ($item->deeper)
        {
            $class .= ' deeper';
        }

        if ($item->parent && ($params->get('endLevel') == '0' || ((int) $params->get('endLevel') > (int) $params->get('startLevel'))))
        {
            $class .= ' uk-parent';
            if ($current_item && $current_item->parent_id == $item->id)
            {
                $class .= ' uk-open';
            }
        }

        echo '<li class="' . $class . '">';

        switch ($item->type) :
            case 'separator':
            case 'component':
            case 'heading':
            case 'url':
                require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                break;

            default:
                require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
                break;
        endswitch;

        if ($item->deeper)
        {
            echo '<ul class="uk-nav-sub">';
        }
        elseif ($item->shallower)
        {
            echo '</li>';
            echo str_repeat('</ul></li>', $item->level_diff);
        }
        else
        {
            echo '</li>';
        }
    }
    ?>
</ul>
