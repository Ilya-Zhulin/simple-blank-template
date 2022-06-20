<?php

// no direct access
defined('_JEXEC') or die;
/*
 * Если требуется вставить свою секцию в файл,
 * добавте имя позиции в этот массив.
 * Add your custom section name to this array,
 * if you want to place it between defaoult positions/
 *
 */
$sb_top_sections_array		 = ['sb-top-a', 'sb-top-b', 'sb-top-c'];
$sb_bottom_sections_array	 = ['sb-bottom-a', 'sb-bottom-b', 'sb-bottom-c'];
$sb_inner_sections_array	 = ['sb-main-top', 'sb-main-bottom', 'sb-sidebar-a', 'sb-sidebar-b', 'sb-main-sidebar-a', 'sb-main-sidebar-b',];
$sb_offcanvas_array			 = ['sb-off-canvas-a', 'sb-off-canvas-b'];
// Variables
$app						 = JFactory::getApplication();
$doc						 = JFactory::getDocument();
$user						 = JFactory::getUser();
$view						 = $app->input->get('view', '', 'string');
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
$wrappersenable		 = $this->params->get('wrappersenable');
$bodyfullheight		 = ($this->params->get('bodyfullheight') > 0) ? ' uk-height-viewport' : '';
$bodyflex			 = ($this->params->get('bodyflex') > 0) ? ' uk-flex uk-flex-column' : '';
$qlenable			 = $this->params->get('qlenable');

// Sections
$container_main			 = $this->params->get('container_main');
$container_width_main	 = $this->params->get('container_width_main');
$grid_main				 = $this->params->get('grid_main');
$grid_classes_main		 = $this->params->get('gridclasses_main');
switch ($grid_main) {
	case '1';
		$grid_classes_main	 = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
		$grid_classes_main	 .= 'uk-grid-collapse';
		break;
	case '2';
		$grid_classes_main	 = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
		$grid_classes_main	 .= 'uk-grid-large';
		break;
	case '3';
		$grid_classes_main	 = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
		$grid_classes_main	 .= 'uk-grid-medium';
		break;
	case '4';
		$grid_classes_main	 = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
		$grid_classes_main	 .= 'uk-grid-small';
		break;
	default;
		break;
}
$grid_classes_main				 = (strlen($grid_classes_main) > 0) ? ' class="' . $grid_classes_main . '"' : '';
$grid_attr_main					 = $this->params->get('gridattrs_main');
$grid_attr_main					 = (strlen($grid_attr_main) > 0) ? ' ' . $grid_attr_main : '';
$addclasses_main				 = $this->params->get('addclasses_main');
$addclasses_main				 = (strlen($addclasses_main) > 0) ? ' class="' . $addclasses_main . '"' : '';
$addattr_main					 = $this->params->get('addattr_main');
$addattr_main					 = (strlen($addattr_main) > 0) ? ' ' . $addattr_main : '';
$addattr_container_main			 = $this->params->get('addattr_container_main');
$addattr_container_main			 = (strlen($addattr_container_main) > 0) ? ' ' . $addattr_container_main : '';
$addclasses_container_main		 = $this->params->get('addclasses_container_main');
$addclasses_container_main		 = (strlen($addclasses_container_main) > 0) ? ' ' . $addclasses_container_main : '';
$sections						 = [];
$sections['sb-main']['isExist']	 = 1;
$positions						 = (array) $this->params->get('positions-location');
if (is_array($positions) && count($positions) > 0) {

	foreach ($positions as $posid => $position) {
		$position											 = (array) $position;
		$sections[strtolower($position['pos-section'])][]	 = $position;
		$sections[$position['pos-section']]['isExist']		 = (isset($sections[$position['pos-section']]['isExist'])) ? $sections[$position['pos-section']]['isExist'] : 0;
		if ((strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name']) > 0) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-left')) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-right')) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-center'))) {
			$sections[strtolower($position['pos-section'])]['isExist'] = 1;
		}
	}
}
//Sidebars
$sb1_main_show			 = $this->params->get('sb1_main_show');
$sb2_main_show			 = $this->params->get('sb2_main_show');
$sb1_show				 = $this->params->get('sb1_show');
$sb2_show				 = $this->params->get('sb2_show');
$sb1_main_position		 = $this->params->get('sb1_main_position');
$sb2_main_position		 = $this->params->get('sb2_main_position');
$sb1_position			 = $this->params->get('sb1_position');
$sb2_position			 = $this->params->get('sb2_position');
$sb1_main_width			 = ($sb1_main_show) ? $this->params->get('sb1_main_width') : 0;
$sb2_main_width			 = ($sb2_main_show) ? $this->params->get('sb2_main_width') : 0;
$sb1_width				 = ($sb1_show) ? $this->params->get('sb1_width') : 0;
$sb2_width				 = ($sb2_show) ? $this->params->get('sb2_width') : 0;
$sb1_addClass			 = ($sb1_show) ? ' ' . $this->params->get('addclasses_sb1') : '';
$sb2_main_addClass		 = ($sb2_show) ? ' ' . $this->params->get('addclasses_sb2_main') : '';
$sb1_main_addClass		 = ($sb1_show) ? ' ' . $this->params->get('addclasses_sb1_main') : '';
$sb2_addClass			 = ($sb2_show) ? ' ' . $this->params->get('addclasses_sb2') : '';
$sb1_main_addAttr		 = ($sb1_show) ? ' ' . $this->params->get('addattr_sb1_main') : '';
$sb2_main_addAttr		 = ($sb2_show) ? ' ' . $this->params->get('addattr_sb2_main') : '';
$sb1_addAttr			 = ($sb1_show) ? ' ' . $this->params->get('addattr_sb1') : '';
$sb2_addAttr			 = ($sb2_show) ? ' ' . $this->params->get('addattr_sb2') : '';
$sb1_main_real_width	 = 0; // Учитывает ширину, только, если сайдбар заполнен
$sb2_main_real_width	 = 0; // Учитывает ширину, только, если сайдбар заполнен
$sb1_real_width			 = 0; // Учитывает ширину, только, если сайдбар заполнен
$sb2_real_width			 = 0; // Учитывает ширину, только, если сайдбар заполнен
// Off-canvases
$offcanvas1_show		 = $this->params->get('offcanvas1_show');
$offcanvas1_position	 = $this->params->get('offcanvas1_position');
$offcanvas1_animation	 = $this->params->get('offcanvas1_animation');
$offcanvas1_position	 = $this->params->get('offcanvas2_position');
$offcanvas1_flip		 = ($offcanvas1_position == '1') ? 'false' : 'true';
$offcanvas1_overlay		 = $this->params->get('offcanvas1_overlay');
$offcanvas2_show		 = $this->params->get('offcanvas2_show');
$offcanvas2_position	 = $this->params->get('offcanvas2_position');
$offcanvas2_animation	 = $this->params->get('offcanvas2_animation');
$offcanvas2_position	 = $this->params->get('offcanvas2_position');
$offcanvas2_flip		 = ($offcanvas2_position == '1') ? 'false' : 'true';
$offcanvas2_overlay		 = $this->params->get('offcanvas2_overlay');

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

// Add JavaScripts
if ($lazysizes == 1) {
	$doc->addScript($tplpath . '/js/lazysizes.js');
}
$doc->addScript($tplpath . '/vendor/uikit/js/uikit.min.js');
$doc->addScript($tplpath . '/vendor/uikit/js/uikit-icons.min.js');
$doc->addScript($tplpath . '/vendor/uikit/js/uikit-custom-icons.min.js');
$doc->addScript($tplpath . '/js/marked.js');
$doc->addScript($tplpath . '/js/theme.js');

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
	$excluded	 = explode(',', $this->params->get('css_exclude_files'));
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
	$section_attr	 = isset($params['addattr_' . $suffix]) ? ' ' . $params['addattr_' . $suffix] : '';
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
	$out			 = '<section id="' . $posName . '" class="uk-section' . $section_class . '"' . $section_attr . '>';

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
					if (isset($section_item['pos-modal']) && $section_item['pos-modal'] > 0) {
						$modal_class = "";
						$modal_class .= ($section_item['pos-modal-center'] > 0) ? "uk-flex-top" : "";
						$modal_class .= (strlen($modal_class) > 0) ? " " : "";
						$modal_class .= 'uk-modal-' . $section_item['pos-modal-size'];
						$modal_class .= (strlen($modal_class) > 1) ? " " : "";
						$modal_class .= $section_item['pos-modal-addclasses-modal'];
						$out		 .= '<div id="' . $section_item['pos-name'] . '-modal" uk-modal="' . $section_item['pos-modal-params'] . '" class="' . $modal_class . '" ' . $section_item['pos-modal-addparams-modal'] . '>';
						$out		 .= '<div class="' . $section_item['pos-modal-addclasses-dialog'] . '" ' . $section_item['pos-modal-addparams-dialog'] . '>';
						if ($section_item['pos-modal-close'] > 0) {
							$close_class = '';
							$close_class .= "uk-modal-close-" . $section_item['pos-modal-close-pos'];
							$close_class .= ($section_item['pos-modal-close-size'] != 'default') ? " uk-close-" . $section_item['pos-modal-close-size'] : "";
							$close_tag	 = ($section_item['pos-modal-close-tag'] == 'a') ? 'a href=""' : 'button type="button"';
							$out		 .= '<' . $close_tag . ' uk-close class="' . $close_class . '"></' . $section_item['pos-modal-close-tag'] . '>';
						}
					}
					if (isset($section_item['pos-navbar']) || isset($section_item['pos-grid'])) {
						if (isset($section_item['pos-navbar']) && $section_item['pos-navbar'] > 0) {
							$out .= '<nav id="' . $pos_name . '-navbar" class="uk-navbar-container';
							if (isset($section_item['pos-navbar-transparent'])) {
								$out .= ' uk-navbar-transparent';
							}
							if (isset($section_item['pos-navbar-addclasses'])) {
								$out .= ' ' . $section_item['pos-navbar-addclasses'];
							}
							if (isset($section_item['pos-navbar-container']) && $section_item['pos-navbar-container'] !== 'none') {
								$out .= '">';
								$out .= '<div class="uk-container';
								if ($section_item['pos-navbar-container'] !== 'default') {
									$out .= ' uk-container-' . $section_item['pos-navbar-container'];
								}
								$out .= '">';
								$out .= '<div uk-navbar="' . $section_item['pos-navbar-params'] . '">';
							} else {
								$out .= '" uk-navbar="' . $section_item['pos-navbar-params'] . '">';
							}
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
					if (isset($section_item['pos-navbar-container']) && $section_item['pos-navbar-container'] !== 'none') {
						$out .= '</div>';
						$out .= '</div>';
					}
					if (isset($section_item['pos-navbar'])) {
						$out .= '</nav>';
					}
					if (isset($section_item['pos-modal']) && $section_item['pos-modal'] > 0) {
						$out .= '</div>';
						$out .= '</div>';
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
