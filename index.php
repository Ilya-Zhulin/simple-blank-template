<?php
/*
 * @package    simple_blank_template
 * @version 6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 18:36
 */

// no direct access
defined('_JEXEC') or die;


use SimpleBlank\Site\Controller\ConfigController;
use SimpleBlank\Site\Renderer\ThemeAlertRenderer;

// Импортируем рендерер

// 1. Инициализация контроллера
$config = new ConfigController();
// 2. Экспорт всех рассчитанных переменных в глобальную область видимости
// Теперь доступны: $sb1_exist, $sb1_real_width, $content_width, $sections, $fraction и т.д.
extract($config->data);
// 3. Создаем алиас для функции дроби, чтобы старый код работал без изменений
// $fraction теперь вызывает метод $config->getFraction()
//$fraction = [$config, 'getFraction'];

// 3. Инициализация рендерера и вывод (представление)
$alertRenderer = new ThemeAlertRenderer();
echo $alertRenderer->renderThemeAlert(!SB_THEME_HAS_ACTIVE);


// В index.php больше НЕТ логики расчета ширины.
// Переменные $sb1_real_width, $content_width и т.д. уже содержат правильные значения из контроллера.
// Если вдруг нужно их переопределить локально, можно сделать это здесь, но обычно не требуется.


?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
    <?php
    if (file_exists(JPATH_THEMES . '/' . $this->template . '/includes/head.php'))
    {
        // Передаем $config в head.php, если там нужна логика
        include JPATH_THEMES . '/' . $this->template . '/includes/head.php';
    }
    ?>
    <jdoc:include type="head"/>
</head>

<body class="sb-<?php echo $view; ?><?php echo ' ' . $pageclass; ?><?php echo $bodyflex; ?>"
      role="document"<?php echo $bodyfullheight; ?>>
<?php
// Wrappers
if (isset($wrappersenable) && $wrappersenable > 0)
{
for ($i = 1;
     $i <= $wrappersenable;
     $i++)
{
?>
<div id="sb-content-wrapper-<?php echo $i; ?>" class="sb-content-wrapper-<?php echo $i; ?>">
    <?php
    }
    }

    // Логика выбора паттерна или секций
    $displayData = ['config' => $config, 'template' => $this];
    if ($sb1_exist || $sb2_exist)
    {
        ?>
        <!-- grid-<?php echo $sb1_height . '-' . $sb2_height; ?> pattern included -->
        <?php
        // В файлы паттернов тоже желательно передавать $config, если они используют сложные функции
        include_once JPATH_THEMES . '/simple_blank/includes/patterns/grid-' . $sb1_height . '-' . $sb2_height . '.php';
    }
    else
    {
        include_once JPATH_THEMES . '/simple_blank/includes/layouts/section/top.php';
        include_once JPATH_THEMES . '/simple_blank/includes/layouts/section/main.php';
        include_once JPATH_THEMES . '/simple_blank/includes/layouts/section/bottom.php';
    }
    include_once JPATH_THEMES . '/simple_blank/includes/layouts/section/offcanvas.php';

    if (file_exists(JPATH_THEMES . '/' . $this->template . '/includes/footer.php'))
    {
        include JPATH_THEMES . '/' . $this->template . '/includes/footer.php';
    }
    ?>

    <jdoc:include type="modules" name="sb-debug"/>

    <?php include JPATH_THEMES . '/' . $this->template . '/includes/analytics.php'; ?>

    <?php
    if (isset($wrappersenable) && $wrappersenable > 0)
    {
    for ($i = 1;
    $i <= $wrappersenable;
    $i++)
    {
    ?>
</div>
<?php
}
}
?>

<?php if ($qlenable == 1): ?>
    <script>
        window.addEventListener('load', () => {
            if (typeof quicklink !== 'undefined') quicklink.listen();
        });
    </script>
<?php endif; ?>

</body>
</html>
