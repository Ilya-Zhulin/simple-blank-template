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
class JFormFieldThemename extends JFormFieldText {

	/**
	 * @var $type    Имя типа
	 */
	protected $type = 'themename';

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
		$html	 = '<div class="input-append">';
		$html	 .= $this->getRenderer($this->layout)->render($this->getLayoutData());
		$html	 .= '<button class="btn" type="button" onclick="Joomla.submitbutton(\'style.apply\');">' . JText::_('COM_TEMPLATES_THEMES_CREATE_FIELDSET_LABEL') . '</button>';
		$html	 .= '</div>';
		return $html;
	}

}
