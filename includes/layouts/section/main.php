<?php
/**
 * Layout: Section Main
 * @package SimpleBlank
 */

use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die('Restricted access');

// Восстанавливаем переменные из контроллера
$displayData = $displayData ?? [];
$config      = $displayData['config'] ?? null;
$template    = $displayData['template'] ?? null;

if ($config)
{
    extract($config->data);
}

if ($hidecomponent == 1): ?>
    <main id="sb-content" class="uk-width-<?php echo $content_width . $main_addclasses; ?>"
          role="main"<?php echo $main_addattr; ?>>

        <?php if ($main_container > 0): ?>
        <div class="uk-container<?php echo ' uk-container-' . $main_container_width . $main_addclasses_container ?>"<?php echo $main_addattr_container ?>>
            <?php endif; ?>

            <?php
            if ($content_width_array[1] > 6): ?>
                <div class="uk-width-1-1">
                    <div class="uk-alert uk-alert-warning">
                        <i class="uk-icon-exclamation-triangle"></i>
                        Sorry, content width class (uk-width-medium-<?php echo $content_width; ?>) is not supported.
                    </div>
                </div>
            <?php else:
                if ($sb1_main_exist || $sb2_main_exist):
                    // Паттерн сетки (пока оставляем include, можно тоже перевести на лейауты позже)
                    $grid_prefix = 'main';
                    include JPATH_THEMES . '/simple_blank/includes/patterns/grid-' . $sb1_main_height . '-' . $sb2_main_height . '.php';
                    $grid_prefix = '';
                else:
                    // Рендерим под-секции через лейауты
                    $layoutBase = JPATH_THEMES . '/simple_blank/includes/layouts/section';
                    echo LayoutHelper::render('main-top', $displayData, $layoutBase);
                    echo LayoutHelper::render('main-main', $displayData, $layoutBase);
                    echo LayoutHelper::render('main-bottom', $displayData, $layoutBase);
                endif;
            endif;
            ?>

            <?php if ($main_container > 0): ?>
        </div>
    <?php endif; ?>

    </main>
<?php endif; ?>
