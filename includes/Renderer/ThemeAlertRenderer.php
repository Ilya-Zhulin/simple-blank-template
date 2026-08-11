<?php
/*
 * @package    simple_blank_template
 * @version 6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 16:03
 */

namespace SimpleBlank\Site\Renderer;

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

class ThemeAlertRenderer
{
	/**
	 * Рендеринг предупреждения о теме
	 *
	 * @param   bool  $showAlert  Флаг необходимости показа
	 *
	 * @return string HTML код или пустая строка
	 */
	public function renderThemeAlert(bool $showAlert): string
	{
		if (!$showAlert)
		{
			return '';
		}

		$html = '<div uk-alert class="uk-alert-warning uk-text-center">';
		$html .= Text::_('TPL_SIMPLE_BLANK_THEME_EXISTS_ALERT');
		$html .= '</div>';

		return $html;
	}
}
