<?php
/*
 * @package    simple_blank_template
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 14:56
 */

/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 */

namespace SimpleBlank\Site\Field;
defined('_JEXEC') or die('Restricted access');

use Joomla\Filesystem\Folder;
use Joomla\Form\Field\ListField;
use Joomla\Language\Text;

/**
 * Поле выбора пользовательской темы (папки в /themes)
 */
class ThemeselectField extends ListField
{

	/**
	 * @var string Тип поля
	 */
	protected $type = 'Themeselect';

	/**
	 * Метод получения опций для списка
	 *
	 * @return array Массив объектов {value, text}
	 */
	protected function getOptions()
	{
		$options = [];

		$templateName = 'simple_blank';
		$themesPath   = JPATH_SITE . '/templates/' . $templateName . '/themes';

		// Проверяем существование папки тем
		if (is_dir($themesPath))
		{
			// Получаем список папок (тем)
			// false = не рекурсивно, true = полные пути не нужны (только имена)
			$folders = Folder::folders($themesPath, '.', false, false);

			if (!empty($folders))
			{
				foreach ($folders as $folder)
				{
					// Пропускаем служебные файлы/папки, если вдруг попадутся
					if ($folder === 'index.html' || $folder === '.gitkeep')
					{
						continue;
					}

					$value = $folder;
					// Красивое название: заменяем дефисы/подчеркивания на пробелы и делаем Заглавными
					$text = ucfirst(str_replace(['-', '_'], ' ', $folder));

					$options[] = (object) [
						'value' => $value,
						'text'  => Text::_($text) // Пытаемся перевести, если есть ключ в языке
					];
				}
			}
		}

		// Если тем нет, добавляем заглушку
		if (empty($options))
		{
			$options[] = (object) [
				'value' => '',
				'text'  => Text::_('TPL_SIMPLE_BLANK_NO_THEMES_AVAILABLE')
			];
		}

		// Важно: объединяем с опциями родителя (если они заданы статически в XML, хотя тут вряд ли)
		// Но для кастомных списков лучше вернуть просто наш массив, чтобы не дублировалось.
		// parent::getOptions() вернет пустой массив или дефолтные, если они есть в XML.
		return array_merge(parent::getOptions(), $options);
	}
}
