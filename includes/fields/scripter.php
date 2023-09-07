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
		$app	 = JFactory::getApplication();
		$jinput	 = $app->input;
		$styleid = $jinput->get('id');
		$db		 = JFactory::getDbo();
		$uri	 = JURI::getInstance();
		$query	 = $db->getQuery(true);
		$query->select($db->quoteName('params'))
				->from($db->quoteName('#__template_styles'))
				->where('id=' . $styleid);
		$db->setQuery($query);
		$result	 = $db->loadResult();
		$params	 = json_decode($result);
		if (isset($params->theme_name) && strlen(trim($params->theme_name)) > 0) {
			$theme_path	 = JPATH_ROOT . '/templates/simple_blank/themes/' . trim($params->theme_name);
			JFolder::create($theme_path);
			JFolder::create($theme_path . '/less');
			JFolder::create($theme_path . '/css');
			JFolder::create($theme_path . '/js');
			$files		 = [
				'index.html'							 => "<h1>&#128683; You are not welcome here</h1>",
				'/less/index.html'						 => "<h1>&#128683; You are not welcome here</h1>",
				'/css/index.html'						 => "<h1>&#128683; You are not welcome here</h1>",
				'/js/index.html'						 => "<h1>&#128683; You are not welcome here</h1>",
				'/less/' . $params->theme_name . '.less' => '/**' . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your less here. It will be included to site style' . PHP_EOL . ' *' . PHP_EOL . '**/',
				'/css/' . $params->theme_name . '.css'	 => "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your css here. It will be included to site style' . PHP_EOL . ' *' . PHP_EOL . '**/',
				'/js/' . $params->theme_name . '.js'	 => "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your js here. It will be included to site JS' . PHP_EOL . ' *' . PHP_EOL . '**/',
				'head_top.php'							 => "<?php" . PHP_EOL . "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your code here. It will be included in top of head' . PHP_EOL . ' *' . PHP_EOL . '**/',
				'head_bottom.php'						 => "<?php" . PHP_EOL . "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your code here. It will be included in bottom of head' . PHP_EOL . ' *' . PHP_EOL . '**/',
				'footer.php'							 => "<?php" . PHP_EOL . "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your code here. It will be included in bottom of page' . PHP_EOL . ' *' . PHP_EOL . '**/',
			];
			foreach ($files as $file => $file_content) {
				file_put_contents($theme_path . '/' . $file, $file_content);
			}
			$result			 = str_replace('"theme_name":"' . $params->theme_name . '",', "", $result); // Создаем и заполняем объект
			$object			 = new stdClass();
			$object->id		 = $styleid;
			$object->params	 = $result;
			$result			 = JFactory::getDbo()->updateObject('#__template_styles', $object, 'id', true);
			$app->enqueueMessage('Тема создана. Выберите её в меню', 'Message');
			$app->redirect($uri->toString());
		}
		if (isset($params->theme_select) && strlen(trim($params->theme_select)) > 0) {
			$text = file_get_contents(JPATH_ROOT . '/templates/simple_blank/less/template.tmp');
			file_put_contents(JPATH_ROOT . '/templates/simple_blank/less/template.less', str_replace('path_to_theme_file', '../themes/' . $params->theme_select . '/less/' . $params->theme_select . '.less', $text));

//			$text = file_get_contents(JPATH_ROOT . '/templates/simple_blank/includes/head.tmp');
//			file_put_contents(JPATH_ROOT . '/templates/simple_blank/includes/head.php', str_replace('path_to_theme_file1', '/templates/simple_blank/themes/' . $params->theme_select . '/head_top.php', $text));
//			$text = file_get_contents(JPATH_ROOT . '/templates/simple_blank/includes/head.php');
//			file_put_contents(JPATH_ROOT . '/templates/simple_blank/includes/head.php', str_replace('path_to_theme_file2', '/templates/simple_blank/themes/' . $params->theme_select . '/head_bottom.php', $text));

			$text = file_get_contents(JPATH_ROOT . '/templates/simple_blank/includes/footer.tmp');
			file_put_contents(JPATH_ROOT . '/templates/simple_blank/includes/footer.php', str_replace('path_to_theme_file', '/templates/simple_blank/themes/' . $params->theme_select . '/footer.php', $text));
		}
	}
}
