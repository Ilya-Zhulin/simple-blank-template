<?php

/*
 * File scripter.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2021
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');

use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filter\OutputFilter;

/**
 * Создаем класс.
 */
class JFormFieldScripter extends JFormFieldHidden {

    /**
     * @var $type    Имя типа
     */
    protected $type = 'scripter';

    /**
     * При отображении поля происходит проверка
     * на наличии его значения. Если значение есть,
     * то создаётся новая тема с таким названием и
     * значение удаляется.
     * Таким образом, будет создаваться новая тема и добавляться в список.
     *
     * @return    Результат вывода типа
     */
    protected function getInput() {

        $app     = Factory::getApplication();
        $db      = Factory::getContainer()->get('DatabaseDriver');
        $uri     = URI::getInstance();
        $jinput  = $app->input;
        $styleid = $jinput->get('id');
        $query   = $db->getQuery(true);
        $query->select($db->quoteName('params'))
                ->from($db->quoteName('#__template_styles'))
                ->where('id=' . $styleid);
        $db->setQuery($query);
        $result  = $db->loadResult();
        $params  = json_decode($result);
        if (isset($params->theme_name) && strlen(trim($params->theme_name)) > 0) {
            $theme_name       = str_replace("-", '_', OutputFilter::stringURLSafe($params->theme_name));
            $theme_path       = JPATH_ROOT . '/templates/simple_blank/themes/' . $theme_name;
            $theme_media_path = JPATH_ROOT . '/media/templates/site/simple_blank/themes/' . $theme_name;
            if (!file_exists($theme_path)) {
                Folder::create($theme_path);
                Folder::create($theme_media_path);
                Folder::create($theme_path . '/html');
                Folder::create($theme_media_path . '/less');
                Folder::create($theme_media_path . '/css');
                Folder::create($theme_media_path . '/js');
                $files = [
                    'index.html'                     => "<h1>&#128683; You are not welcome here</h1>",
                    '/less/index.html'               => "<h1>&#128683; You are not welcome here</h1>",
                    '/css/index.html'                => "<h1>&#128683; You are not welcome here</h1>",
                    '/js/index.html'                 => "<h1>&#128683; You are not welcome here</h1>",
                    '/less/' . $theme_name . '.less' => '/**' . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your less here. It will be included to site style' . PHP_EOL . ' *' . PHP_EOL . '**/',
                    '/css/' . $theme_name . '.css'   => "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your css here. It will be included to site style' . PHP_EOL . ' *' . PHP_EOL . '**/',
                    '/js/' . $theme_name . '.js'     => "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your js here. It will be included to site JS' . PHP_EOL . ' *' . PHP_EOL . '**/'
                ];
                foreach ($files as $file => $file_content) {
                    file_put_contents($theme_media_path . '/' . $file, $file_content);
                }
                $files2 = [
                    'index.html'       => "<h1>&#128683; You are not welcome here</h1>",
                    '/html/index.html' => "<h1>&#128683; You are not welcome here</h1>",
                    'head_top.php'     => "<?php" . PHP_EOL . "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your code here. It will be included in top of head' . PHP_EOL . ' *' . PHP_EOL . '**/',
                    'head_bottom.php'  => "<?php" . PHP_EOL . "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your code here. It will be included in bottom of head' . PHP_EOL . ' *' . PHP_EOL . '**/',
                    'footer.php'       => "<?php" . PHP_EOL . "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your code here. It will be included in bottom of page' . PHP_EOL . ' *' . PHP_EOL . '**/',
                ];
                foreach ($files2 as $file => $file_content) {
                    file_put_contents($theme_path . '/' . $file, $file_content);
                }
                $params->theme_select = $theme_name;
                $params->theme_name   = '';
                $object               = new stdClass();
                $object->id           = $styleid;
                $object->params       = json_encode($params);
                $object->home         = 1;
                $upd                  = $db->updateObject('#__template_styles', $object, 'id');
                $text                 = file_get_contents($theme_media_path . '/less/theme.less');
                $text                 = str_replace(PHP_EOL . '@import "../themes/' . $theme_name . '/less/' . $theme_name . '.less";', '', $text);
                $text                 .= PHP_EOL . '@import "../themes/' . $theme_name . '/less/' . $theme_name . '.less";';
                File::write($theme_media_path . '/less/theme.less', $text);
                $app->enqueueMessage('Тема создана. Выберите её в меню', 'Message');
                $app->redirect($uri->getPath() . '?' . $uri->getQuery());
            }
        }
    }
}
