<?php
/**
 * @package    simple_blank_template
 * @version    3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
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
}