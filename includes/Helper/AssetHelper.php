<?php
/*
 * @package    simple_blank_template
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 15:02
 */

namespace SimpleBlank\Site\Helper;

use DirectoryIterator;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

class AssetHelper
{
	protected $doc;
	protected $params;
	protected $tplpath;

	public function __construct($params, $tplpath)
	{
		$this->doc     = Factory::getApplication()->getDocument();
		$this->params  = $params;
		$this->tplpath = $tplpath;
	}

	public function initialize()
	{
		$this->removeBootstrap();
		$this->removeMetaTags();
		$this->loadFonts();
		$this->loadScripts();
		$this->handleLess();
		$this->loadStylesheets();
		$this->setVerification();
	}

	protected function removeBootstrap()
	{
		if ($this->params->get('killbootstrap', 1) == '1')
		{
			$headData = $this->doc->getHeadData();
			unset($headData['scripts'][JURI::root() . 'media/jui/js/bootstrap.min.js']);
			// Для Joomla 4/5 путь может отличаться, лучше использовать WebAssetManager,
			// но для совместимости оставим массив scripts пока так.
			$this->doc->setHeadData($headData);
		}
	}

	protected function removeMetaTags()
	{
		$headData = $this->doc->getHeadData();
		unset($headData['metaTags']['http-equiv']);
		$this->doc->setHeadData($headData);
		$this->doc->setGenerator(null);
	}

	protected function loadFonts()
	{
		if ($this->params->get('googlefont', 0))
		{
			$fontName = $this->params->get('googlefontname', 'Open+Sans');
			$this->doc->addStyleSheet('//fonts.googleapis.com/css?family=' . urlencode($fontName) . '&subset=cyrillic,latin');
		}
	}

	protected function loadScripts()
	{
		// jQuery
		HTMLHelper::_('jquery.framework');

		if ($this->params->get('lazysizes', 0))
		{
			$this->doc->addScript($this->tplpath . '/js/lazysizes.js');
		}

		// UIKit
		$this->doc->addScript($this->tplpath . '/vendor/uikit/js/uikit.min.js');
		$this->doc->addScript($this->tplpath . '/vendor/uikit/js/uikit-icons.min.js');
		$this->doc->addScript($this->tplpath . '/vendor/uikit/js/uikit-custom-icons.min.js');
		$this->doc->addScript($this->tplpath . '/js/theme.js');

		// Quicklink
		if ($this->params->get('qlenable', 1))
		{
			// Тут можно добавить логику подключения quicklink, если он есть в js/
		}
	}

	protected function handleLess()
	{
		$lessCompile = $this->params->get('less_acompile', 0);

		if ($lessCompile)
		{
			// Логика Live Compile (подключение less.js и файлов)
			// Реализуем позже, когда будем делать фичу
			// $this->doc->addScript(...);
		}
		else
		{
			$this->loadStylesheets();
		}
	}

	protected function loadStylesheets()
	{
		$cssPath       = JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/css/';
		$excluded      = explode(',', $this->params->get('css_exclude_files', ''));
		$templateFound = false;

		if (is_dir($cssPath))
		{
			$files = new DirectoryIterator($cssPath);
			foreach ($files as $file)
			{
				if ($file->isFile() && $file->getExtension() === 'css')
				{
					$filename = $file->getFilename();
					if ($filename === 'template.css')
					{
						$templateFound = true;
						continue;
					}
					if (!in_array($filename, $excluded))
					{
						$this->doc->addStyleSheet($this->tplpath . '/css/' . $filename);
					}
				}
				elseif ($file->isDir() && !$file->isDot())
				{
					// Рекурсивный обход подпапок если нужно
					$subFiles = new DirectoryIterator($file->getPathname());
					foreach ($subFiles as $subFile)
					{
						if ($subFile->isFile() && $subFile->getExtension() === 'css')
						{
							if (!in_array($subFile->getFilename(), $excluded))
							{
								$this->doc->addStyleSheet($this->tplpath . '/css/' . $file->getFilename() . '/' . $subFile->getFilename());
							}
						}
					}
				}
			}
		}

		if ($templateFound)
		{
			$minCss = $this->tplpath . '/css/template.min.css';
			// Проверка существования файла через file_exists требует полного пути
			if (file_exists(JPATH_ROOT . '/templates/' . Factory::getApplication()->getTemplate() . '/css/template.min.css'))
			{
				$this->doc->addStyleSheet($minCss);
			}
			else
			{
				$this->doc->addStyleSheet($this->tplpath . '/css/template.css');
			}
		}
	}

	protected function setVerification()
	{
		$this->doc->setMetadata('google-site-verification', $this->params->get('googleverification'));
		$this->doc->setMetadata('yandex-verification', $this->params->get('yandexverification'));
		$this->doc->setMetadata('msvalidate.01', $this->params->get('bingverification'));
	}
}
