<?php

/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 */

// Подключение требуемых файлов
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Создаем класс.
 */
class JFormFieldTheme_select extends JFormFieldList {

	/**
	 * @var $type    Имя типа
	 */
	protected $type = 'theme_select';

	/**
	 * Метод, определяющий что будет выводить параметр
	 *
	 * @return    Результат вывода типа
	 */
	protected function getOptions() {
		$db		 = JFactory::getDbo();
		$db->setQuery('select extension_id from #__extensions where type="template" and name="simple_blank"');
		$ext_id	 = $db->loadResult();
		$html	 = '';
		$class	 = $this->getAttribute('class');
		$id		 = $this->id;
		$onclick = ($this->onclick) ? ' onclick="' . $this->onclick . '"' : '';
		$title	 = ' title="' . JText::_('TPL_SIMPLE_BLANK_LESS_COMPILE_BUTTON_DESCRIPTION') . '"';
		$html	 = '<button type="button" id="' . $id . '" class="' . $class . '"' . $onclick . $title . ' data-extension-id="' . $ext_id . '">' . JText::_('TPL_SIMPLE_BLANK_LESS_COMPILE_BUTTON_CAPTION') . '</button>';
		return $html;
	}

}
