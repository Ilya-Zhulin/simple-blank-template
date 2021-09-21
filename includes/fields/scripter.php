<?php

/*
 * File text.php is programmed
 * specially for mk-graphics by
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
		$uri	 = JFactory::getURI();
		$query	 = $db->getQuery(true);
		$query->select($db->quoteName('params'))
				->from($db->quoteName('#__template_styles'))
				->where('id=' . $styleid);
		$db->setQuery($query);
		$result	 = $db->loadResult();
		$params	 = json_decode($result);
		if (isset($params->theme_name) && strlen(trim($params->theme_name)) > 0) {
			$theme_path	 = JPATH_ROOT . '/templates/simple_blank/less/themes/' . trim($params->theme_name);
			JFolder::create($theme_path);
			$files		 = [
				'index.html'					 => "<h1>&#128683; You are not welcome here</h1>",
				$params->theme_name . '.less'	 => "/**" . PHP_EOL . ' * File created for theme ' . $params->theme_name . PHP_EOL . ' * in Simple Blank tepmlate' . PHP_EOL . ' *' . PHP_EOL . ' * Put your less here. It will be included to site style' . PHP_EOL . ' *' . PHP_EOL . '**/',
			];
			foreach ($files as $file => $file_content) {
				file_put_contents($theme_path . '/' . $file, $file_content);
			}
			$result			 = str_replace('"theme_name":"' . $params->theme_name . '",', "", $result); // Создаем и заполняем объект
			$object			 = new stdClass();
			$object->id		 = $styleid; // должен быть валидный первичный ключ
			$object->params	 = $result;
// Обновляем данные, используя id в качестве первичного ключа
			$result			 = JFactory::getDbo()->updateObject('#__template_styles', $object, 'id', true);
			$app->enqueueMessage('Тема создана. Выберите её в меню', 'Message');
			$app->redirect($uri->toString());
		}
		if (isset($params->theme_select) && strlen(trim($params->theme_select)) > 0) {
			$text = file_get_contents(JPATH_ROOT . '/templates/simple_blank/less/template.tmp');
			file_put_contents(JPATH_ROOT . '/templates/simple_blank/less/template.less', str_replace('path_to_theme_file', 'themes/' . $params->theme_select . '/' . $params->theme_select . '.less', $text));
		}
	}

}
