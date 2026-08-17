<?php
/*
 * @package    simple_blank_template
 * @version 6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 */

namespace SimpleBlank\Site\Field;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use SimpleBlank\Site\Service\ThemeManager;

/**
 * Поле-кнопка «Собрать прод-копию темы в media».
 * Действие выполняется ТОЛЬКО по явному переходу по ссылке кнопки
 * (параметр productionsync в запросе) — никаких автоматических копирований.
 * @since   6.0.0
 */
class ProductionSyncField extends FormField
{
	/**
	 * @var string Тип поля
	 */
	protected $type = 'productionsync';

	/**
	 * Метод, определяющий что будет выводить параметр
	 *
	 * @return string HTML код кнопки и статуса зеркала
	 */
	protected function getInput()
	{
		$app  = Factory::getApplication();
		$input = $app->input;

		// Получаем ID стиля (шаблона)
		$styleId = $input->getInt('id', 0);
		if (!$styleId)
		{
			return '';
		}

		$themeManager = ThemeManager::getInstance();

		// Явная команда: переход по ссылке кнопки
		if ($input->getInt('productionsync', 0) && $themeManager->hasActiveTheme())
		{
			$themeManager->productionCopy();
			$app->enqueueMessage(Text::_('TPL_SIMPLE_BLANK_PRODUCTION_SYNC_DONE'), 'success');
		}

		if (!$themeManager->hasActiveTheme())
		{
			return '';
		}

		// Проверяем наличие зеркала для предупреждения
		$mirrorBase = JPATH_ROOT . '/media/templates/site/' . $app->getTemplate() . '/themes/' . $themeManager->getActiveTheme();
		$hasMirror  = is_dir($mirrorBase) && (count(glob($mirrorBase . '/css/*.css')) > 0 || count(glob($mirrorBase . '/js/*.js')) > 0);

		$html = '<div>';

		if (!$hasMirror)
		{
			$html .= '<p class="alert alert-warning">' . Text::_('TPL_SIMPLE_BLANK_PRODUCTION_SYNC_WARN') . '</p>';
		}

		$url  = rtrim(Uri::root(), '/') . '/administrator/index.php?option=com_templates&view=style&layout=edit&id=' . $styleId . '&productionsync=1';
		$html .= '<a href="' . htmlspecialchars($url, ENT_QUOTES) . '" class="btn btn-primary">' . Text::_('TPL_SIMPLE_BLANK_PRODUCTION_SYNC_BUTTON') . '</a>';

		$html .= '</div>';

		return $html;
	}
}
