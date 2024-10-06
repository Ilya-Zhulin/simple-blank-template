<?php

/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 */

// Подключение требуемых файлов
jimport('joomla.form.formfield');

/**
 * Создаем класс.
 */
class JFormFieldButton extends JFormField {

	/**
	 * @var $type    Имя типа
	 */
	protected $type = 'button';

	/**
	 * Метод, определяющий что будет выводить параметр
	 *
	 * @return    Результат вывода типа
	 */
	protected function getInput() {
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
