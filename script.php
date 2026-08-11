<?php
/**
 * @package    simple_blank_template
 * @version    6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use SimpleBlank\Site\Service\ThemeManager;

class Tpl_SimpleBlankInstallerScript implements InstallerScriptInterface
{
	private string $minimumPhp = '8.3';
	private string $minimumJoomla = '6.0';

	public function preflight($type, $parent): void
	{
		if (version_compare(PHP_VERSION, $this->minimumPhp, '<'))
		{
			throw new \RuntimeException(
				Text::sprintf('TPL_SIMPLE_BLANK_INSTALL_PHP_VERSION', $this->minimumPhp, PHP_VERSION)
			);
		}

		if (version_compare(JVERSION, $this->minimumJoomla, '<'))
		{
			throw new \RuntimeException(
				Text::sprintf('TPL_SIMPLE_BLANK_INSTALL_JOOMLA_VERSION', $this->minimumJoomla, JVERSION)
			);
		}
	}

	public function install($parent): void
	{
	}

	public function update($parent): void
	{
	}

	public function uninstall($parent): void
	{
	}

	public function postflight($type, $parent): void
	{
		if ($type === 'uninstall')
		{
			return;
		}

		$this->migrateLegacyThemes();

		$app = Factory::getApplication();
		$template = $app->getTemplate(true);
		$params = $template->params;

		if ($params->get('production_mode', 0))
		{
			$themeManager = ThemeManager::getInstance();

			if ($themeManager->hasActiveTheme())
			{
				$themeManager->productionCopy();
				$app->enqueueMessage(Text::_('TPL_SIMPLE_BLANK_INSTALL_PRODUCTION_MODE'), 'info');
			}
		}

		Log::add(Text::_('TPL_SIMPLE_BLANK_INSTALL_DONE'), Log::INFO, 'jerror');
	}

	/**
	 * Миграция тем со старых версий шаблона:
	 * - досоздаёт папки скелета темы (images, fonts, html + index.html),
	 *   которые появились в новой версии;
	 * - удаляет продовые бандлы theme-*.css старых схем (это копии,
	 *   первоисточник всегда в папке темы).
	 */
	private function migrateLegacyThemes(): void
	{
		$themesRoot = JPATH_THEMES . '/simple_blank/themes';

		if (is_dir($themesRoot))
		{
			foreach (glob($themesRoot . '/*', GLOB_ONLYDIR) ?: [] as $themePath)
			{
				$this->ensureThemeDirs($themePath);
			}
		}

		// Устаревшие продовые бандлы старых версий (J3-корень шаблона и media)
		foreach ([JPATH_THEMES . '/simple_blank/css', JPATH_ROOT . '/media/templates/site/simple_blank/css'] as $legacyCssPath)
		{
			if (!is_dir($legacyCssPath))
			{
				continue;
			}

			foreach (scandir($legacyCssPath) ?: [] as $file)
			{
				if (strpos($file, 'theme-') === 0)
				{
					@unlink($legacyCssPath . '/' . $file);
				}
			}
		}
	}

	/**
	 * Досоздать недостающие папки скелета темы.
	 *
	 * @param   string  $themePath  Полный путь к папке темы
	 */
	private function ensureThemeDirs(string $themePath): void
	{
		foreach (['images', 'fonts', 'html'] as $dir)
		{
			if (is_dir($themePath . '/' . $dir))
			{
				continue;
			}

			Folder::create($themePath . '/' . $dir);
			File::write($themePath . '/' . $dir . '/index.html', '<h1>&#128683; You are not welcome here</h1>');
		}
	}
}