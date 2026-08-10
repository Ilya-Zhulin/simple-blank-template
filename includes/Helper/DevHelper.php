<?php
/*
 * @package    simple_blank_template
 * @version 3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 */

namespace SimpleBlank\Site\Helper;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Помощник режима разработки шаблона.
 *
 * Включается параметром шаблона devmode или параметром ?sb_dev=1 в URL.
 * В режиме разработки в разметку добавляются скрытые HTML-теги с данными
 * о позициях модулей и файлах, которые выводят соответствующие блоки контента.
 */
class DevHelper
{
	/**
	 * Включён ли режим разработки.
	 *
	 * @return bool
	 */
	public static function isEnabled(): bool
	{
		$app = Factory::getApplication();

		return (bool) $app->getTemplate(true)->params->get('devmode', 0)
			|| $app->input->get('sb_dev') === '1';
	}

	/**
	 * Скрытый HTML-тег секции (области макета) с данными для разработчика.
	 *
	 * @param   string  $posName  Название секции/позиции
	 * @param   string  $file     Абсолютный путь к файлу, который выводит секцию (обычно __FILE__)
	 *
	 * @return string
	 */
	public static function section(string $posName, string $file = ''): string
	{
		if (!self::isEnabled())
		{
			return '';
		}

		$fileAttr = $file !== '' ? ' data-file="' . htmlspecialchars(self::relFile($file), ENT_QUOTES) . '"' : '';

		return "\n"
			. '<div class="sb-dev sb-dev--section" data-section="' . htmlspecialchars($posName, ENT_QUOTES) . '"' . $fileAttr . ' hidden></div>'
			. "\n";
	}

	/**
	 * Возвращает вставку <jdoc:include type="modules"> для позиции.
	 * В режиме разработки перед ней добавляется скрытый HTML-тег
	 * с названием позиции, количеством модулей и файлом-исходником.
	 *
	 * @param   string  $posName  Название позиции модулей
	 * @param   string  $file     Абсолютный путь к файлу, который выводит позицию (обычно __FILE__)
	 * @param   string  $style    Стиль jdoc:include (если не 'xhtml' — добавляется атрибут style)
	 *
	 * @return string
	 */
	public static function modules(string $posName, string $file = '', string $style = 'xhtml'): string
	{
		$jdoc = '<jdoc:include type="modules" name="' . $posName . '"'
			. ($style !== 'xhtml' ? ' style="' . $style . '"' : '')
			. ' />';

		if (!self::isEnabled())
		{
			return $jdoc;
		}

		$count    = Factory::getApplication()->getDocument()->countModules($posName);
		$fileAttr = $file !== '' ? ' data-file="' . htmlspecialchars(self::relFile($file), ENT_QUOTES) . '"' : '';

		return "\n"
			. '<span class="sb-dev sb-dev--position" data-position="' . htmlspecialchars($posName, ENT_QUOTES) . '" data-modules="' . (int) $count . '"' . $fileAttr . ' hidden></span>'
			. "\n"
			. $jdoc
			. "\n";
	}

	/**
	 * Относительный путь к файлу от корня шаблона (для data-file).
	 * Если файл вне шаблона — возвращается как есть.
	 *
	 * @param   string  $absolutePath  Абсолютный путь (__FILE__)
	 *
	 * @return string
	 */
	public static function relFile(string $absolutePath): string
	{
		$base           = JPATH_THEMES . '/' . Factory::getApplication()->getTemplate();
		$absolutePath   = str_replace('\\', '/', $absolutePath);
		$base           = rtrim(str_replace('\\', '/', $base), '/') . '/';

		if (strpos($absolutePath, $base) === 0)
		{
			return substr($absolutePath, strlen($base));
		}

		return $absolutePath;
	}
}