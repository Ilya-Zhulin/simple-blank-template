<?php
/*
 * @package    simple_blank_template
 * @version    6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 */

namespace SimpleBlank\Site\Field;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;

/**
 * Скрытое поле для загрузки JS админки (LESS компиляция, ширины, навигация по позициям)
 * @since   6.0.0
 */
class PositionnavField extends FormField
{
	protected $type = 'positionnav';

	public function renderField($options = array())
	{
		return '<div style="display:none">' . $this->getInput() . '</div>';
	}

	protected function getInput()
	{
		$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

		$wa->registerAndUseScript('tpl.jscript', '/templates/simple_blank/includes/Field/jscript.js');
		$wa->registerAndUseScript('tpl.positionnav', '/templates/simple_blank/includes/Field/position-nav.js');
		$wa->registerAndUseScript('tpl.sectionnav', '/templates/simple_blank/includes/Field/section-nav.js');

		return '<input type="hidden" name="' . $this->name . '" value="1">';
	}
}
