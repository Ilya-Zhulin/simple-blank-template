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
use SimpleBlank\Site\Service\ThemeManager;

class Tpl_SimpleBlankInstallerScript
{
	public function postflight($type, $parent)
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
				$app->enqueueMessage('Production mode active: theme CSS copied to css/ folder.', 'info');
			}
		}
	}
}