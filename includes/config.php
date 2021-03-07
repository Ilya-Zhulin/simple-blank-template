<?php

// no direct access
defined('_JEXEC') or die;
//include_once JPATH_ROOT . '/jbdump/init.php';
/*
 * Если требуется вставить свою секцию в файл,
 * добавте имя позиции в этот массив.
 * Add your custom section name to this array,
 * if you want to place it between defaoult positions/
 *
 */
$sb_top_sections_array		 = ['sb-top-a', 'sb-top-b', 'sb-top-c'];
$sb_bottom_sections_array	 = ['sb-bottom-a', 'sb-bottom-b', 'sb-bottom-c'];
$sb_inner_sections_array	 = ['sb-main-top', 'sb-main-bottom', 'sb-sidebar-a', 'sb-sidebar-b'];
$sb_offcanvas_array			 = ['sb-off-canvas-a', 'sb-off-canvas-b'];
// Variables
$app						 = JFactory::getApplication();
$doc						 = JFactory::getDocument();
$user						 = JFactory::getUser();
$view						 = JRequest::getString('view');
$this->language				 = $doc->language;
$this->direction			 = $doc->direction;
$headdata					 = $doc->getHeadData();
$menu						 = $app->getMenu();
$active						 = $app->getMenu()->getActive();
$params						 = $app->getParams();
$pageclass					 = $params->get('pageclass_sfx');
$tplpath					 = $this->baseurl . '/templates/' . $this->template;
$tplparams					 = $this->params->toArray();

// Parameters
$hidecomponent		 = $this->params->get('hidecomponent', 0);
$lazysizes			 = $this->params->get('lazysizes', 0);
$googlefont			 = $this->params->get('googlefont', 0);
$googlefontname		 = $this->params->get('googlefontname');
$googleid			 = $this->params->get('googleid');
$yandexid			 = $this->params->get('yandexid');
$googleverification	 = $this->params->get('googleverification');
$yandexverification	 = $this->params->get('yandexverification');
$bingverification	 = $this->params->get('bingverification');
$wrappersenabled	 = $tplparams['wrappersenable'];
$wrappersenabled	 = $tplparams['wrappersenable'];

// Sections
$container_main					 = $tplparams['container_main'];
$container_width_main			 = $tplparams['container_width_main'];
$addclasses_main				 = (strlen($tplparams['addclasses_main']) > 0) ? ' class="' . $tplparams['addclasses_main'] . '"' : '';
$addattr_main					 = (strlen($tplparams['addattr_main']) > 0) ? ' ' . $tplparams['addattr_main'] : '';
$addattr_container_main			 = (strlen($tplparams['addattr_container_main']) > 0) ? ' ' . $tplparams['addattr_container_main'] : '';
$addclasses_container_main		 = (strlen($tplparams['addclasses_container_main']) > 0) ? ' ' . $tplparams['addclasses_container_main'] : '';
$sections						 = [];
$sections['sb-main']['isExist']	 = 1;
$positions						 = $tplparams['positions-location'];
foreach ($positions as $posid => $position) {
	$sections[strtolower($position['pos-section'])][]	 = $position;
	$sections[$position['pos-section']]['isExist']		 = (isset($sections[$position['pos-section']]['isExist'])) ? $sections[$position['pos-section']]['isExist'] : 0;
	if ((strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name']) > 0) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-left')) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-right')) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-center'))) {
		$sections[strtolower($position['pos-section'])]['isExist'] = 1;
	}
}
//layouts
//$layouts_top_a = $this->params->get('layouts_top-a');
//$layouts_top_b = $this->params->get('layouts_top-b');
//$layouts_top_c = $this->params->get('layouts_top-c');
//$layouts_bottom_a = $this->params->get('layouts_bottom-a');
//$layouts_bottom_b = $this->params->get('layouts_bottom-b');
//$layouts_bottom_c = $this->params->get('layouts_bottom-c');
//$layouts_main_top = $this->params->get('layouts_main-top');
//$layouts_main_bottom = $this->params->get('layouts_main-bottom');
//Sidebars
$sb1_show		 = $tplparams['sb1_show'];
$sb2_show		 = $tplparams['sb2_show'];
$sb1_position	 = $tplparams['sb1_position'];
$sb2_position	 = $tplparams['sb2_position'];
$sb1_width		 = ($sb1_show) ? $tplparams['sb1_width'] : 0;
$sb2_width		 = ($sb2_show) ? $tplparams['sb2_width'] : 0;
$sb1_real_width	 = 0; // Учитывает ширину, только, если сайдбар заполнен
$sb2_real_width	 = 0; // Учитывает ширину, только, если сайдбар заполнен
// Off-canvases
$offcanvas1_show		 = $tplparams['offcanvas1_show'];
$offcanvas1_position	 = $tplparams['offcanvas1_position'];
$offcanvas1_animation	 = $tplparams['offcanvas1_animation'];
$offcanvas1_position	 = $tplparams['offcanvas2_position'];
$offcanvas1_flip		 = ($offcanvas1_position == '1') ? 'false' : 'true';
$offcanvas1_overlay		 = $tplparams['offcanvas1_overlay'];
$offcanvas2_show		 = $tplparams['offcanvas2_show'];
$offcanvas2_position	 = $tplparams['offcanvas2_position'];
$offcanvas2_animation	 = $tplparams['offcanvas2_animation'];
$offcanvas2_position	 = $tplparams['offcanvas2_position'];
$offcanvas2_flip		 = ($offcanvas2_position == '1') ? 'false' : 'true';
$offcanvas2_overlay		 = $tplparams['offcanvas2_overlay'];

//Less
$less_acompile	 = $this->params->get('less_acompile', 0);
//Remove Bootstrap
$disable_bs		 = $this->params->get('killbootstrap', 1);
if ($disable_bs == '1') {
	unset($headdata['scripts']['/media/jui/js/bootstrap.min.js']);
}
// Remove generator tag
$this->setGenerator(null);
// Disable mootools
// unset($doc->_scripts[$this->baseurl .'/media/system/js/mootools-core.js']);
// unset($doc->_scripts[$this->baseurl .'/media/system/js/mootools-more.js']);
// unset($doc->_scripts[$this->baseurl .'/media/system/js/caption.js']);
// unset($doc->_scripts[$this->baseurl .'/media/system/js/core.js']);
// unset($doc->_scripts[$this->baseurl .'/media/system/js/tabs-state.js']);
// unset($doc->_scripts[$this->baseurl .'/media/system/js/validate.js']);
// unset($doc->_scripts[$this->baseurl .'/media/com_finder/js/autocompleter.js']);
// Remove deprecated meta-data (HTML5)
unset($headdata['metaTags']['http-equiv']);

$doc->setHeadData($headdata);

// Load jQuery
JHtml::_('jquery.framework');

// Add StyleSheets
if ($googlefont == 1) {
	$doc->addStyleSheet('//fonts.googleapis.com/css?family=' . $googlefontname . '&subset=cyrillic,latin');
}

$doc->addStyleSheet($tplpath . '/css/codemirror.css');
$doc->addStyleSheet($tplpath . '/js/flatpickr/flatpickr.min.css');
$doc->addStyleSheet($tplpath . '/js/flatpickr/ie.css');
//$doc->addStyleSheet($tplpath . '/js/select/css/cs-skin-elastic.css');
// Add JavaScripts
if ($lazysizes == 1) {
	$doc->addScript($tplpath . '/js/lazysizes.js');
}
$doc->addScript($tplpath . '/vendor/uikit/js/uikit.min.js');
$doc->addScript($tplpath . '/vendor/uikit/js/uikit-icons.min.js');
$doc->addScript($tplpath . '/vendor/uikit/js/uikit-custom-icons.min.js');
$doc->addScript($tplpath . '/js/codemirror.min.js');
$doc->addScript($tplpath . '/js/marked.js');
$doc->addScript($tplpath . '/js/theme.js');
$doc->addScript($tplpath . '/js/flatpickr/flatpickr.min.js');
$doc->addScript($tplpath . '/js/flatpickr/ru.js');
$doc->addScript($tplpath . '/js/select/js/classie.js');
$doc->addScript($tplpath . '/js/select/js/selectFx.js');
$doc->addScript('//aishek.github.io/jquery-animateNumber/javascripts/jquery.animateNumber.js');


// Add site verification (Google, Yandex, Bing)
$doc->setMetadata('google-site-verification', $googleverification);
$doc->setMetadata('yandex-verification', $yandexverification);
$doc->setMetadata('msvalidate.01', $bingverification);

// Less Autocompilation
if ($less_acompile == 1) {
//	$less_path = '/templates/' . $this->template . '/less/';
//	$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js');
//	$doc->addCustomTag('<link rel="stylesheet/less" type="text/css" href="' . $less_path . 'template.less" />');
} else {
	// CSS including
	$css_path	 = JPATH_THEMES . '/' . $this->template . '/css/';
	$excluded	 = explode(',', $tplparams['css_exclude_files']);
	$template	 = 0;
	if (is_dir($css_path)) {
		if ($dh = opendir($css_path)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($css_path . $file) === 'file') {
					$ext = (explode('.', $file));
					$ext = end($ext);
					if ($ext === 'css' && $file !== 'template.css' && !in_array($file, $excluded)) {
						$doc->addStyleSheet($tplpath . '/css/' . $file);
					} elseif ($file == 'template.css') {
						$template = 1;
					}
				} else {
					if ($file != '.' && $file != '..') {
						if ($dh1 = opendir($css_path . $file)) {

							while (($file1 = readdir($dh1)) !== false) {
								if (filetype($css_path . $file . '/' . $file1) === 'file') {
									$ext1	 = (explode('.', $file1));
									$ext1	 = end($ext1);
									if ($ext1 === 'css' && !in_array($file1, $excluded)) {
										$doc->addStyleSheet($tplpath . '/css/' . $file . '/' . $file1);
									}
								}
							}
						}
					}
				}
			}
			if ($template == 1) {
				$doc->addStyleSheet($tplpath . '/css/template.css');
			}
			closedir($dh);
		}
	}
}

function _buildPosition($template, $posName, $params, $sections) {
	$posName		 = strtolower($posName);
	$suffix			 = str_replace('sb-', '', $posName);
	$section_class	 = isset($params['addclasses_' . $suffix]) ? ' ' . $params['addclasses_' . $suffix] : '';
	$section_class	 .= isset($params['color_' . $suffix]) ? ' uk-section-' . $params['color_' . $suffix] : '';
	if (isset($params['size_' . $suffix])) {
		switch ($params['size_' . $suffix]) {
			case 'default':
				$section_class	 .= '';
				break;
			case '0':
				$section_class	 .= ' uk-padding-remove-vertical';
				break;
			default:
				$section_class	 .= ' uk-section-' . $params['size_' . $suffix];
		}
	}
	$section_class	 .= (isset($params['overlap_' . $suffix]) && $params['overlap_' . $suffix] == '1') ? ' uk-section-overlap' : '';
	$out			 = '<section id="' . $posName . '" class="uk-section' . $section_class . '">';

	if (isset($params['container_' . $suffix]) && $params['container_' . $suffix] !== '0') {
		$out .= '<div class="uk-container';
		if ($params['container_' . $suffix] == '1') {
			$out .= ' uk-container-center';
		}
		switch ($params['container_width_' . $suffix]) {
			case 'max':
				break;
			default:
				$out .= ' uk-container-' . $params['container_width_' . $suffix];
				break;
		}
		$out .= '">';
	}
	if (isset($sections[$posName]) || isset($sections[$posName . '-left']) || isset($sections[$posName . '-right']) || isset($sections[$posName . '-center'])) {
		foreach ($sections[$posName] as $section_item) {
			if (is_array($section_item)) {
				$pos_name = strtolower($section_item["pos-name"]);
				if ($template->countModules($section_item["pos-name"]) ||
						(
						(isset($section_item['pos-navbar']) && ($template->countModules($section_item["pos-name"] . '-left') ||
						$template->countModules($section_item["pos-name"] . '-center') ||
						$template->countModules($section_item["pos-name"] . '-right') ))
						)
				) {
					if (isset($section_item['pos-sticky'])) {
						$out .= '<div id="' . $pos_name . '-sticky" uk-sticky="' . $section_item['pos-sticky-params'] . '">';
					}
					if (isset($section_item['pos-dropdown']) && $section_item['pos-dropdown'] > 0) {
						$out .= '<div id="' . $section_item['pos-name'] . '-dropdown" uk-dropdown="' . $section_item['pos-dropdown-params'] . '" class="' . $section_item['pos-dropdown-addclasses'] . '">';
					}
					if (isset($section_item['pos-navbar']) || isset($section_item['pos-grid'])) {
						if (isset($section_item['pos-navbar'])) {
							$out .= '<nav id="' . $pos_name . '-navbar" class="uk-navbar-container';
							if (isset($section_item['pos-navbar-transparent'])) {
								$out .= ' uk-navbar-transparent';
							}
							$out .= '" uk-navbar="' . $section_item['pos-navbar-params'] . '">';
						}
//                        JBDump($section_item);
						if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1') {
							$grid_params	 = (isset($section_item['pos-grid-params']) && strlen($section_item['pos-grid-params']) > 0) ? $section_item['pos-grid-params'] : '';
							$grid_addparams	 = (isset($section_item['pos-grid-addparams']) && strlen($section_item['pos-grid-addparams']) > 0) ? ' ' . $section_item['pos-grid-addparams'] : '';
							$grid_class		 = '';
							$grid_class		 .= ((isset($section_item['pos-grid-gap-h']) && isset($section_item['pos-grid-gap-v'])) && ($section_item['pos-grid-gap-h'] != $section_item['pos-grid-gap-v'])) ? ' uk-grid-column-' . $section_item['pos-grid-gap-h'] . ' uk-grid-row-' . $section_item['pos-grid-gap-v'] : '';
							$grid_class		 .= ((isset($section_item['pos-grid-gap-h']) && isset($section_item['pos-grid-gap-v'])) && ($section_item['pos-grid-gap-h'] == $section_item['pos-grid-gap-v'])) ? ' uk-grid-' . $section_item['pos-grid-gap-h'] : '';
							$grid_class		 .= (isset($section_item['pos-grid-divider']) && $section_item['pos-grid-divider'] > 0) ? ' uk-grid-divider' : '';
							$grid_class		 .= (isset($section_item['pos-grid-addclasses']) && strlen($section_item['pos-grid-addclasses']) > 0) ? ' ' . $section_item['pos-grid-addclasses'] : '';
							$grid_class		 = (strlen(trim($grid_class)) > 0) ? ' class="' . $grid_class . '"' : '';
							$out			 .= '<div uk-grid="' . $grid_params . '"';
							$out			 .= $grid_addparams;
							$out			 .= $grid_class;
							$out			 .= '>';
						}
						if ($template->countModules($section_item["pos-name"] . '-left')) {
							$out .= '<div class="uk-navbar-left">';
							$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-left" />';
							$out .= '</div>';
						}
						if ($template->countModules($section_item["pos-name"] . '-center')) {
							$out .= '<div class="uk-navbar-center">';
							$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-center" />';
							$out .= '</div>';
						}
						if ($template->countModules($section_item["pos-name"] . '-right')) {
							$out .= '<div class="uk-navbar-right">';
							$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-right" />';
							$out .= '</div>';
						}
					}
					$out .= '<jdoc:include type="modules" name="' . $pos_name . '" />';
					if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1') {
						$out .= '</div>';
					}
					if (isset($section_item['pos-navbar-center']) && $section_item['pos-navbar-center'] === '1') {
						$out .= '</div>';
					}
					if (isset($section_item['pos-navbar'])) {
						$out .= '</nav>';
					}
					if (isset($section_item['pos-dropdown']) && $section_item['pos-dropdown'] > 0) {
						$out .= '</div>';
					}
					if (isset($section_item['pos-sticky'])) {
						$out .= '</div>';
					}
				}
			}
		}
	} else {
		$out .= '<jdoc:include type="modules" name="' . $posName . '" />';
	}
	if (isset($params['container_' . $suffix]) && $params['container_' . $suffix] !== '0') {
		$out .= '</div>';
	}
	$out .= '</section>';
	return $out;
}
