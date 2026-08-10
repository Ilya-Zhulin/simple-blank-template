<?php
/*
 * @package    DEV
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 05.05.2026, 20:30
 */

// File: /templates/simple_blank/includes/Controller/ConfigController.php

namespace SimpleBlank\Site\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use SimpleBlank\Site\Service\ThemeManager;

defined('_JEXEC') or die;

class ConfigController
{
	public $data = [];
	protected $app;
	protected $template;
	protected $params;
	protected $doc;

	// Публичный массив данных для экспорта в index.php
	protected $tplpath;
	protected $mediaUrl;

	public function __construct()
	{
		$this->app      = Factory::getApplication();
		$this->template = $this->app->getTemplate(true);
		$this->params   = $this->template->params;
		$this->doc      = $this->app->getDocument();
		$this->tplpath  = Uri::root() . 'templates/' . $this->template->template;
		$this->mediaUrl = Uri::root() . 'media/templates/site/' . $this->template->template;

		$this->init();
	}


	public function getTemplate()
	{
		return $this->template;
	}

	protected function init()
	{
		// 1. Базовые переменные
		$this->data['view'] = $this->app->input->get('view', '', 'string');

		$activeMenu              = $this->app->getMenu()->getActive();
		$pageParams              = $activeMenu ? $activeMenu->getParams() : $this->app->getParams();
		$this->data['pageclass'] = $pageParams->get('pageclass_sfx', '');

		// 2. Глобальные настройки
		$this->data['hidecomponent']  = $this->params->get('hidecomponent', 0);
		$this->data['googlefont']     = $this->params->get('googlefont', 0);
		$this->data['googlefontname'] = $this->params->get('googlefontname', 'Open+Sans');
		$this->data['wrappersenable'] = $this->params->get('wrappersenable', 0);
		$this->data['bodyfullheight'] = $this->params->get('bodyfullheight') ? ' uk-height-viewport' : '';
		$this->data['bodyflex']       = $this->params->get('bodyflex') ? ' uk-flex uk-flex-column' : '';
		$this->data['qlenable']       = $this->params->get('qlenable', 1);
		$this->data['less_acompile']  = $this->params->get('less_acompile', 0);
		$this->data['patternclass']   = $this->params->get('patternclass', '');
		$this->data['googleid']       = $this->params->get('googleid', '');
		$this->data['yandexid']       = $this->params->get('yandexid', '');

		// 3. Подготовка данных
		$this->prepareSections();
		$this->prepareSidebars();
		$this->prepareOffcanvas();
		$this->prepareMainGrid();

		// 4. РАСЧЕТ ШИРИНЫ (Вся логика перенесена сюда)
		$this->calculateWidths();

		// 5. Управление ассетами
		$this->manageAssets();
		ThemeManager::registerConstants();

// Обработка POST title (если нужно)
		$input = Factory::getApplication()->input;
		if ($input->getMethod() === 'POST' && $input->has('page_title'))
		{
			$doc = Factory::getApplication()->getDocument();
			$doc->setTitle($input->get('page_title', '', 'STRING'));
		}
	}

	protected function prepareSections()
	{
		$sections                       = [];
		$sections['sb-main']['isExist'] = 1;

		$positions = (array) $this->params->get('positions-location');

		if (is_array($positions) && count($positions) > 0)
		{
			foreach ($positions as $posid => $position)
			{
				$position = (array) $position;
				$posName  = (string) ($position['pos-name'] ?? '');

				// Пропускаем позиции, в названии которых нет ни одной буквы
				if (!preg_match('/\p{L}/u', $posName))
				{
					continue;
				}

				$secName  = strtolower((string) ($position['pos-section'] ?? ''));

				if (!isset($sections[$secName]))
				{
					$sections[$secName]            = [];
					$sections[$secName]['isExist'] = 0;
				}

				$sections[$secName][] = $position;

				if (strlen($posName) > 0)
				{
					$hasModule = $this->doc->countModules($posName) > 0
						|| $this->doc->countModules($posName . '-left')
						|| $this->doc->countModules($posName . '-right')
						|| $this->doc->countModules($posName . '-center');

					if ($hasModule)
					{
						$sections[$secName]['isExist'] = 1;
					}
				}
			}
		}
		$this->data['sections'] = $sections;
	}

	// --- ФУНКЦИЯ extParams (перенесена из index.php) ---

	protected function prepareSidebars()
	{
		$fillSidebar = function ($prefix) {
			$show = $this->params->get($prefix . '_show');

			return [
				'show'       => $show,
				'tag'        => $this->params->get($prefix . '_tag'),
				'position'   => $this->params->get($prefix . '_position'),
				'width'      => $show ? $this->params->get($prefix . '_width') : 0,
				'height'     => $show ? $this->params->get($prefix . '_height') : 1,
				'addClass'   => $show ? ' ' . $this->params->get($prefix . '_addclasses') : '',
				'addAttr'    => $show ? ' ' . $this->params->get($prefix . '_addattr') : '',
				'real_width' => 0 // Будет рассчитано позже
			];
		};

		$sb1      = $fillSidebar('sidebar-a');
		$sb2      = $fillSidebar('sidebar-b');
		$sb1_main = $fillSidebar('main-sidebar-a');
		$sb2_main = $fillSidebar('main-sidebar-b');

		// Экспортируем плоские переменные
		$this->data['sb1_show']     = $sb1['show'];
		$this->data['sb1_tag']      = $sb1['tag'];
		$this->data['sb1_position'] = $sb1['position'];
		$this->data['sb1_width']    = $sb1['width'];
		$this->data['sb1_height']   = $sb1['height'];
		$this->data['sb1_addClass'] = $sb1['addClass'];
		$this->data['sb1_addAttr']  = $sb1['addAttr'];

		$this->data['sb2_show']     = $sb2['show'];
		$this->data['sb2_tag']      = $sb2['tag'];
		$this->data['sb2_position'] = $sb2['position'];
		$this->data['sb2_width']    = $sb2['width'];
		$this->data['sb2_height']   = $sb2['height'];
		$this->data['sb2_addClass'] = $sb2['addClass'];
		$this->data['sb2_addAttr']  = $sb2['addAttr'];

		$this->data['sb1_main_show']     = $sb1_main['show'];
		$this->data['sb1_main_position'] = $sb1_main['position'];
		$this->data['sb1_main_width']    = $sb1_main['width'];
		$this->data['sb1_main_height']   = $sb1_main['height'];

		$this->data['sb2_main_show']     = $sb2_main['show'];
		$this->data['sb2_main_position'] = $sb2_main['position'];
		$this->data['sb2_main_width']    = $sb2_main['width'];
		$this->data['sb2_main_height']   = $sb2_main['height'];
	}

	protected function prepareOffcanvas()
	{
		$prefixes = ['off-canvas-a', 'off-canvas-b'];
		$keys     = ['offcanvas1', 'offcanvas2'];

		foreach ($prefixes as $index => $prefix)
		{
			$key  = $keys[$index];
			$show = $this->params->get($prefix . '_show');
			$pos  = $this->params->get($prefix . '_position');

			$this->data[$key . '_show']           = $show;
			$this->data[$key . '_tag']            = $this->params->get($prefix . '_tag');
			$this->data[$key . '_position']       = $pos;
			$this->data[$key . '_animation']      = $this->params->get($prefix . '_animation');
			$this->data[$key . '_flip']           = ($pos == '1') ? 'false' : 'true';
			$this->data[$key . '_overlay']        = $this->params->get($prefix . '_overlay');
			$this->data[$key . '_close']          = $this->params->get($prefix . '_close_button');
			$this->data[$key . '_close_large']    = $this->params->get($prefix . '_close_button_large');
			$this->data[$key . '_addclasses']     = $this->params->get($prefix . '_addclasses');
			$this->data[$key . '_addattr']        = $this->params->get($prefix . '_addattr');
			$this->data[$key . '_bar_addclasses'] = $this->params->get($prefix . '_bar_addclasses');
			$this->data[$key . '_bar_addattr']    = $this->params->get($prefix . '_bar_addattr');
		}
	}

	protected function prepareMainGrid()
	{
		$mainGrid    = $this->params->get('main_grid', 0);
		$gridClasses = $this->params->get('main_addclasses_grid', '');

		switch ($mainGrid)
		{
			case '1':
				$gridClasses .= ' uk-grid-collapse';
				break;
			case '2':
				$gridClasses .= ' uk-grid-large';
				break;
			case '3':
				$gridClasses .= ' uk-grid-medium';
				break;
			case '4':
				$gridClasses .= ' uk-grid-small';
				break;
		}

		$this->data['main_grid_classes'] = strlen(trim($gridClasses)) ? ' class="' . trim($gridClasses) . '"' : '';
		$this->data['main_grid_attr']    = $this->params->get('main_addattrs_grid', '');
		if (strlen(trim($this->data['main_grid_attr'])) > 0)
		{
			$this->data['main_grid_attr'] = ' ' . trim($this->data['main_grid_attr']);
		}

		$this->data['main_container']            = $this->params->get('main_container', '');
		$this->data['main_container_width']      = $this->params->get('main_container_width', '');
		$this->data['main_addclasses']           = ' ' . $this->params->get('main_addclasses', '');
		$this->data['main_addattr']              = ' ' . $this->params->get('main_addattr', '');
		$this->data['main_addclasses_container'] = ' ' . $this->params->get('main_addclasses_container', '');
		$this->data['main_addattr_container']    = ' ' . $this->params->get('main_addattr_container', '');
	}

	protected function calculateWidths()
	{
		// 1. Определяем существование сайдбаров
		$sb1_exist = $this->data['sb1_show'] == 1 &&
			($this->doc->countModules('sb-sidebar-a') ||
				(isset($this->data['sections']['sb-sidebar-a']) && $this->data['sections']['sb-sidebar-a']['isExist'] > 0));

		$sb2_exist = $this->data['sb2_show'] == 1 &&
			($this->doc->countModules('sb-sidebar-b') ||
				(isset($this->data['sections']['sb-sidebar-b']) && $this->data['sections']['sb-sidebar-b']['isExist'] > 0));

		$sb1_main_exist = $this->data['sb1_main_show'] == 1 &&
			($this->doc->countModules('sb-main-sidebar-a') ||
				(isset($this->data['sections']['sb-main-sidebar-a']) && $this->data['sections']['sb-main-sidebar-a']['isExist'] > 0));

		$sb2_main_exist = $this->data['sb2_main_show'] == 1 &&
			($this->doc->countModules('sb-main-sidebar-b') ||
				(isset($this->data['sections']['sb-main-sidebar-b']) && $this->data['sections']['sb-main-sidebar-b']['isExist'] > 0));

		// Сохраняем флаги exist
		$this->data['sb1_exist']      = $sb1_exist;
		$this->data['sb2_exist']      = $sb2_exist;
		$this->data['sb1_main_exist'] = $sb1_main_exist;
		$this->data['sb2_main_exist'] = $sb2_main_exist;

		// 2. Рассчитываем реальную ширину
		$this->data['sb1_real_width']      = $sb1_exist ? $this->data['sb1_width'] : 0;
		$this->data['sb2_real_width']      = $sb2_exist ? $this->data['sb2_width'] : 0;
		$this->data['sb1_main_real_width'] = $sb1_main_exist ? $this->data['sb1_main_width'] : 0;
		$this->data['sb2_main_real_width'] = $sb2_main_exist ? $this->data['sb2_main_width'] : 0;

		// 3. Рассчитываем дроби контента используя метод класса
		$content_width                     = $this->getFraction(60 - $this->data['sb1_real_width'] - $this->data['sb2_real_width']);
		$this->data['content_width']       = $content_width;
		$this->data['content_width_array'] = explode('-', $content_width);

		$main_content_width                     = $this->getFraction(60 - $this->data['sb1_main_real_width'] - $this->data['sb2_main_real_width']);
		$this->data['main_content_width']       = $main_content_width;
		$this->data['main_content_width_array'] = explode('-', $main_content_width);
	}

	public function getFraction($nominator, $divider = 60)
	{
		$gcf = function ($a, $b) use (&$gcf) {
			return ($b > 0) ? $gcf($b, $a % $b) : $a;
		};

		return $nominator / ($factor = $gcf($nominator, $divider)) . '-' . $divider / $factor;
	}

	protected function manageAssets()
	{
		$wa = $this->doc->getWebAssetManager();

		$wa->useScript('template.simple_blank.uikit');
		$wa->useScript('template.simple_blank.uikit-icons');

		if (file_exists(JPATH_ROOT . '/templates/' . $this->template->template . '/vendor/uikit/js/uikit-custom-icons.min.js'))
		{
			$wa->useScript('template.simple_blank.uikit-custom-icons');
		}

		$wa->useScript('template.simple_blank.theme');

		if ($this->params->get('qlenable', 1))
		{
			$wa->useScript('template.simple_blank.quicklink');
		}

		$this->doc->setGenerator(null);

		if ($this->data['googlefont'])
		{
			$this->doc->addStyleSheet('https://fonts.googleapis.com/css?family=' . urlencode($this->data['googlefontname']) . '&subset=cyrillic,latin');
		}

		$this->doc->setMetadata('google-site-verification', $this->params->get('googleverification'));
		$this->doc->setMetadata('yandex-verification', $this->params->get('yandexverification'));
		$this->doc->setMetadata('msvalidate.01', $this->params->get('bingverification'));

		if ($this->data['less_acompile'])
		{
			// Логика Live Compile (закомментирована)
		}
		else
		{
			$themeManager = ThemeManager::getInstance();

			// Версионирование CSS: добавляем ?v=<время изменения файла> чтобы избежать кеширования
			// Не работает в Production Mode
			$cssVersioning = $themeManager->isProductionMode() ? 0 : (int) $this->params->get('css_versioning', 0);
			$addCss        = function ($url, $path = '') use ($cssVersioning)
			{
				if ($cssVersioning && $path !== '' && file_exists($path))
				{
					$url .= '?v=' . filemtime($path);
				}

				$this->doc->addStyleSheet($url);
			};

			// Production Mode: загружаем скомпилированный CSS из корня
			if ($themeManager->isProductionMode() && $themeManager->hasActiveTheme())
			{
				$prodFile     = 'theme-' . $themeManager->getActiveTheme() . '.css';
				$prodFilePath = JPATH_ROOT . '/media/templates/site/' . $this->template->template . '/css/' . $prodFile;

				// Скомпилированного файла нет - собираем его из CSS активной темы
				if (!file_exists($prodFilePath))
				{
					$themeManager->productionCopy();
				}

				if (file_exists($prodFilePath))
				{
					$addCss($this->mediaUrl . '/css/' . $prodFile, $prodFilePath);

					return;
				}
			}

			if ($themeManager->hasActiveTheme())
			{
				$cssPath = $themeManager->getThemeBasePath() . '/css/';
				$cssUrl  = $this->tplpath . '/themes/' . $themeManager->getActiveTheme() . '/css/';
			}
			else
			{
				$cssPath = JPATH_ROOT . '/media/templates/site/' . $this->template->template . '/css/';
				$cssUrl  = $this->mediaUrl . '/css/';
			}

			$excluded      = explode(',', $this->params->get('css_exclude_files', ''));
			$excluded      = array_merge($excluded, ['uikit.css', 'uikit.min.css']);
			$templateFound = false;
			$hasMin        = false;

			if (is_dir($cssPath))
			{
				$dh = opendir($cssPath);
				while (($file = readdir($dh)) !== false)
				{
					if (filetype($cssPath . $file) === 'file')
					{
						$extParts = explode('.', $file);
						$ext      = end($extParts);
						if ($ext === 'css' && $file !== 'template.css' && $file !== 'template.min.css' && !in_array($file, $excluded))
						{
							$addCss($cssUrl . $file, $cssPath . $file);
						}
						elseif ($file == 'template.css')
						{
							$templateFound = true;
						}
						elseif ($file == 'template.min.css')
						{
							$hasMin = true;
						}
					}
					else
					{
						if ($file != '.' && $file != '..')
						{
							$dh1 = opendir($cssPath . $file);
							while (($file1 = readdir($dh1)) !== false)
							{
								if (filetype($cssPath . $file . '/' . $file1) === 'file')
								{
									$extParts1 = explode('.', $file1);
									$ext1      = end($extParts1);
									if ($ext1 === 'css' && !in_array($file1, $excluded))
									{
										$addCss($cssUrl . $file . '/' . $file1, $cssPath . $file . '/' . $file1);
									}
								}
							}
							closedir($dh1);
						}
					}
				}
				closedir($dh);
			}

			if ($hasMin)
			{
				$addCss($cssUrl . 'template.min.css', $cssPath . 'template.min.css');
			}
			elseif ($templateFound)
			{
				$addCss($cssUrl . 'template.css', $cssPath . 'template.css');
			}
		}
	}

	// --- ВСЯ ЛОГИКА РАСЧЕТА ШИРИНЫ ПЕРЕНЕСЕНА СЮДА ---

	public function getDoc()
	{
		return $this->doc;
	}

	public function extParams(&$tplparams, $param, $value)
	{
		if (is_array($param))
		{
			foreach ($param as $par)
			{
				$tplparams[$par] .= ($tplparams[$par] === '') ? $value : ' ' . $value;
			}
		}
		else
		{
			$tplparams[$param] .= ($tplparams[$param] === '') ? $value : ' ' . $value;
		}

		return $tplparams;
	}

	// Метод _buildPosition остается здесь или может быть вынесен в Renderer,
	// но пока оставим его публичным методом контроллера для вызова из index.php или include файлов

	public function _buildPosition($posName, $sections)
	{
		$template      = $this->template;
		$params        = $this->params;
		$posName       = strtolower($posName);
		$suffix        = str_replace('sb-', '', $posName);
		$section_class = $suffix;
		$section_class .= isset($params[$suffix . '_addclasses']) ? ' ' . $params[$suffix . '_addclasses'] : '';
		$section_class .= isset($params[$suffix . '_color']) ? ' uk-section-' . $params[$suffix . '_color'] : '';
		$section_attr  = isset($params[$suffix . '_addattr']) ? ' ' . $params[$suffix . '_addattr'] : '';
		$section_tag   = isset($params[$suffix . '_tag']) ? $params[$suffix . '_tag'] : 'section';
		if (isset($params[$suffix . '_size']))
		{
			switch ($params[$suffix . '_size'])
			{
				case 'default':
					$section_class .= '';
					break;
				case '0':
					$section_class .= ' uk-padding-remove-vertical';
					break;
				default:
					$section_class .= ' uk-section-' . $params[$suffix . '_size'];
			}
		}
		$section_class .= (isset($params[$suffix . '_overlap']) && $params[$suffix . '_overlap'] == '1') ? ' uk-section-overlap' : '';
		$out           = '<' . $section_tag . ' id="' . $posName . '" class="' . $section_class . '"' . $section_attr . '>';

		if (isset($params[$suffix . '_container']) && $params[$suffix . '_container'] !== '0')
		{
			$out .= '<div class="uk-container';
			if ($params[$suffix . '_container'] == '1')
			{
				$out .= ' uk-container-center';
			}
			switch ($params[$suffix . '_container_width'])
			{
				case 'max':
					break;
				default:
					$out .= ' uk-container-' . $params[$suffix . '_container_width'];
					break;
			}
			$out .= '">';
		}
		if (isset($sections[$posName]) || isset($sections[$posName . '-left']) || isset($sections[$posName . '-right']) || isset($sections[$posName . '-center']))
		{
			foreach ($sections[$posName] as $section_item)
			{
				if (is_array($section_item))
				{
					$pos_name = strtolower($section_item["pos-name"]);
					if ($this->doc->countModules($pos_name) && isset($section_item['pos-container']) && $section_item['pos-container'] > 0)
					{
						$out .= '<div class="uk-container';
						if ($section_item['pos-container'] == 1)
						{
							$out .= ' uk-container-center';
						}
						$out .= ' uk-container-' . $section_item['pos-container_width'];
						$out .= strlen(trim($section_item['pos-container-addclasses'])) > 0 ? ' ' . trim($section_item['pos-container-addclasses']) : '';
						$out .= '"';
						$out .= strlen(trim($section_item['pos-container-addparams'])) > 0 ? ' ' . trim($section_item['pos-container-addparams']) : '';
						$out .= '>';
					}
					if ($this->doc->countModules($section_item["pos-name"]) ||
						(
							isset($section_item['pos-navbar']) && ($this->doc->countModules($section_item["pos-name"] . '-left') ||
								$this->doc->countModules($section_item["pos-name"] . '-center') ||
								$this->doc->countModules($section_item["pos-name"] . '-right'))
						)
					)
					{
						if (isset($section_item['pos-sticky']))
						{
							$out .= '<div id="' . $pos_name . '-sticky" uk-sticky="' . $section_item['pos-sticky-params'] . '">';
						}
						if (isset($section_item['pos-dropdown']) && $section_item['pos-dropdown'] > 0)
						{
							$out .= '<div id="' . $section_item['pos-name'] . '-dropdown" uk-dropdown="' . $section_item['pos-dropdown-params'] . '" class="' . $section_item['pos-dropdown-addclasses'] . '">';
						}
						if (isset($section_item['pos-modal']) && $section_item['pos-modal'] > 0)
						{
							$modal_class = "";
							$modal_class .= ($section_item['pos-modal-center'] > 0) ? "uk-flex-top" : "";
							$modal_class .= (strlen($modal_class) > 0) ? " " : "";
							$modal_class .= 'uk-modal-' . $section_item['pos-modal-size'];
							$modal_class .= (strlen($modal_class) > 1) ? " " : "";
							$modal_class .= $section_item['pos-modal-addclasses-modal'];
							$out         .= '<div id="' . $section_item['pos-name'] . '-modal" uk-modal="' . $section_item['pos-modal-params'] . '" class="' . $modal_class . '" ' . $section_item['pos-modal-addparams-modal'] . '>';
							$out         .= '<div class="' . $section_item['pos-modal-addclasses-dialog'] . '" ' . $section_item['pos-modal-addparams-dialog'] . '>';
							if ($section_item['pos-modal-close'] > 0)
							{
								$close_class = '';
								$close_class .= "uk-modal-close-" . $section_item['pos-modal-close-pos'];
								$close_class .= ($section_item['pos-modal-close-size'] != 'default') ? " uk-close-" . $section_item['pos-modal-close-size'] : "";
								$close_tag   = ($section_item['pos-modal-close-tag'] == 'a') ? 'a href=""' : 'button type="button"';
								$out         .= '<' . $close_tag . ' uk-close class="' . $close_class . '"></' . $section_item['pos-modal-close-tag'] . '>';
							}
						}
						if (isset($section_item['pos-navbar']) || isset($section_item['pos-grid']))
						{
							if (isset($section_item['pos-navbar']) && $section_item['pos-navbar'] > 0)
							{
								$out .= '<nav id="' . $pos_name . '-navbar" class="uk-navbar-container';
								if (isset($section_item['pos-navbar-transparent']))
								{
									$out .= ' uk-navbar-transparent';
								}
								if (isset($section_item['pos-navbar-addclasses']))
								{
									$out .= ' ' . $section_item['pos-navbar-addclasses'];
								}
								if (isset($section_item['pos-navbar-container']) && $section_item['pos-navbar-container'] !== 'none')
								{
									$out .= '">';
									$out .= '<div class="uk-container';
									if ($section_item['pos-navbar-container'] !== 'default')
									{
										$out .= ' uk-container-' . $section_item['pos-navbar-container'];
									}
									$out .= '">';
									$out .= '<div uk-navbar="' . $section_item['pos-navbar-params'] . '">';
								}
								else
								{
									$out .= '" uk-navbar="' . $section_item['pos-navbar-params'] . '">';
								}
							}
							if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1')
							{
								$grid_params    = (isset($section_item['pos-grid-params']) && strlen($section_item['pos-grid-params']) > 0) ? $section_item['pos-grid-params'] : '';
								$grid_addparams = (isset($section_item['pos-grid-addparams']) && strlen($section_item['pos-grid-addparams']) > 0) ? ' ' . $section_item['pos-grid-addparams'] : '';
								$grid_class     = '';
								$grid_class     .= ((isset($section_item['pos-grid-gap-h']) && isset($section_item['pos-grid-gap-v'])) && ($section_item['pos-grid-gap-h'] != $section_item['pos-grid-gap-v'])) ? ' uk-grid-column-' . $section_item['pos-grid-gap-h'] . ' uk-grid-row-' . $section_item['pos-grid-gap-v'] : '';
								$grid_class     .= ((isset($section_item['pos-grid-gap-h']) && isset($section_item['pos-grid-gap-v'])) && ($section_item['pos-grid-gap-h'] == $section_item['pos-grid-gap-v'])) ? ' uk-grid-' . $section_item['pos-grid-gap-h'] : '';
								$grid_class     .= (isset($section_item['pos-grid-divider']) && $section_item['pos-grid-divider'] > 0) ? ' uk-grid-divider' : '';
								$grid_class     .= (isset($section_item['pos-grid-addclasses']) && strlen($section_item['pos-grid-addclasses']) > 0) ? ' ' . $section_item['pos-grid-addclasses'] : '';
								$grid_class     = (strlen(trim($grid_class)) > 0) ? ' class="' . $grid_class . '"' : '';
								$out            .= '<div uk-grid="' . $grid_params . '"';
								$out            .= $grid_addparams;
								$out            .= $grid_class;
								$out            .= '>';
							}
							if ($this->doc->countModules($section_item["pos-name"] . '-left'))
							{
								$out .= '<div class="uk-navbar-left">';
								$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-left" />';
								$out .= '</div>';
							}
							if ($this->doc->countModules($section_item["pos-name"] . '-center'))
							{
								$out .= '<div class="uk-navbar-center">';
								$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-center" />';
								$out .= '</div>';
							}
							if ($this->doc->countModules($section_item["pos-name"] . '-right'))
							{
								$out .= '<div class="uk-navbar-right">';
								$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-right" />';
								$out .= '</div>';
							}
						}
						$out .= '<jdoc:include type="modules" name="' . $pos_name . '" />';
						if ($this->doc->countModules($pos_name) && isset($section_item['pos-container']) && $section_item['pos-container'] > 0)
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1')
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-navbar-center']) && $section_item['pos-navbar-center'] === '1')
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-navbar-container']) && $section_item['pos-navbar-container'] !== 'none')
						{
							$out .= '</div>';
							$out .= '</div>';
						}
						if (isset($section_item['pos-navbar']))
						{
							$out .= '</nav>';
						}
						if (isset($section_item['pos-modal']) && $section_item['pos-modal'] > 0)
						{
							$out .= '</div>';
							$out .= '</div>';
						}
						if (isset($section_item['pos-dropdown']) && $section_item['pos-dropdown'] > 0)
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-sticky']))
						{
							$out .= '</div>';
						}
					}
				}
			}
		}
		else
		{
			$out .= '<jdoc:include type="modules" name="' . $posName . '" />';
		}
		if (isset($params[$suffix . '_container']) && $params[$suffix . '_container'] !== '0')
		{
			$out .= '</div>';
		}
		$out .= '</' . $section_tag . '>';

		return $out;
	}

	/**
	 * Проверка, выбрана ли активная тема в настройках шаблона.
	 * Возвращает true, если тема НЕ выбрана или некорректна.
	 *
	 * @return bool True, если нужно показать предупреждение (тема не выбрана).
	 */
	public function checkDefaultTheme()
	{
		// Получаем значение параметра выбора темы
		// В твоем XML это field name="theme_select"
		$selectedTheme = $this->params->get('theme_select', '');

		// Если параметр пуст, значит тема не выбрана (или сброшена на default/none, если они скрыты)
		if (empty($selectedTheme))
		{
			return true; // Показать алерт
		}

		// Дополнительная проверка: существует ли физически файл выбранной темы?
		// Это защитит от ситуации, когда тема выбрана в БД, но файлы удалены вручную.
		$themePath = JPATH_THEMES . '/' . $this->template->template . '/themes/' . basename($selectedTheme);

		if (!file_exists($themePath))
		{
			return true; // Показать алерт (файлы темы не найдены)
		}

		return false; // Всё ок, тема выбрана и существует
	}
}
