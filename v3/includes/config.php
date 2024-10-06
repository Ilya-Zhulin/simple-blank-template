<?php

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Version;

/*
 * Если требуется вставить свою секцию в файл,
 * добавьте имя позиции в этот массив.
 * Add your custom section name to this array,
 * if you want to place it between defaoult positions/
 *
 */


$gcf = function ($a, $b = 60) use (&$gcf) {
    return (int) ($b > 0 ? $gcf($b, $a % $b) : $a);
};

$fraction = function ($nominator, $divider = 60) use (&$gcf) {
    return $nominator / ($factor = $gcf($nominator, $divider)) . '-' . $divider / $factor;
};

$sb_top_sections_array     = ['sb-top-a', 'sb-top-b', 'sb-top-c'];
$sb_bottom_sections_array  = ['sb-bottom-a', 'sb-bottom-b', 'sb-bottom-c'];
$sb_sidebar_sections_array = ['sb-sidebar-a', 'sb-sidebar-b'];
$sb_inner_sections_array   = ['sb-main-top', 'sb-main-bottom', 'sb-main-sidebar-a', 'sb-main-sidebar-b'];
$sb_offcanvas_array        = ['sb-off-canvas-a', 'sb-off-canvas-b'];
// Variables
$app                       = Factory::getApplication();
$doc                       = Factory::getDocument();
$user                      = Factory::getUser();
$view                      = $app->input->get('view', '', 'string');
$this->language            = $doc->language;
$this->direction           = $doc->direction;
$headdata                  = $doc->getHeadData();
$menu                      = $app->getMenu();
$active                    = $app->getMenu()->getActive();
$params                    = $app->getParams();
$pageclass                 = $params->get('pageclass_sfx');
$tplpath                   = $this->baseurl . '/templates/' . $this->template;
$tplparams                 = $this->params->toArray();
$grid_prefix               = ''; // For pattern including
// Parameters
$hidecomponent             = $this->params->get('hidecomponent', 0);
$lazysizes                 = $this->params->get('lazysizes', 0);
$patternclass              = $this->params->get('patternclass', "");
$googlefont                = $this->params->get('googlefont', 0);
$googlefontname            = $this->params->get('googlefontname');
$googleid                  = $this->params->get('googleid');
$yandexid                  = $this->params->get('yandexid');
$googleverification        = $this->params->get('googleverification');
$yandexverification        = $this->params->get('yandexverification');
$bingverification          = $this->params->get('bingverification');
$wrappersenable            = $this->params->get('wrappersenable');
$bodyfullheight            = ($this->params->get('bodyfullheight') > 0) ? ' uk-height-viewport' : '';
$bodyflex                  = ($this->params->get('bodyflex') > 0) ? ' uk-flex uk-flex-column' : '';
$qlenable                  = $this->params->get('qlenable');
$bodygrid_gap              = $this->params->get('bodygrid_gap');
$bodygrid_class            = $this->params->get('bodygrid_class', '');
$bodygrid_class            = (strlen($bodygrid_class) > 0) ? ' ' . $bodygrid_class : '';
$bodygrid_class            = ' class="uk-grid-' . $bodygrid_gap . $bodygrid_class . '"';
$bodygrid_attr             = $this->params->get('bodygrid_attr');
$bodygrid_attr             = (strlen($bodygrid_class) > 0) ? ' ' . $bodygrid_attr : '';

// Sections
$main_container       = $this->params->get('main_container', '');
$main_container_width = $this->params->get('main_container_width', '');
$main_grid            = $this->params->get('main_grid', 0);
$main_grid_classes    = $this->params->get('main_gridclasses', '');
switch ($main_grid) {
    case '1';
        $main_grid_classes = (strlen($main_grid_classes) > 0) ? $main_grid_classes . ' ' : '';
        $main_grid_classes .= 'uk-grid-collapse';
        break;
    case '2';
        $grid_classes_main = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
        $grid_classes_main .= 'uk-grid-large';
        break;
    case '3';
        $grid_classes_main = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
        $grid_classes_main .= 'uk-grid-medium';
        break;
    case '4';
        $grid_classes_main = (strlen($grid_classes_main) > 0) ? $grid_classes_main . ' ' : '';
        $grid_classes_main .= 'uk-grid-small';
        break;
    default;
        break;
}
$main_grid_classes              = (strlen($main_grid_classes) > 0) ? ' class="' . $main_grid_classes . '"' : '';
$main_grid_attr                 = $this->params->get('main_addattrs_grid', '');
$main_grid_attr                 = (strlen($main_grid_attr) > 0) ? ' ' . $main_grid_attr : '';
$main_addclasses                = $this->params->get('main_addclasses', '');
$main_addclasses                = (strlen($main_addclasses) > 0) ? ' ' . $main_addclasses : '';
$main_addattr                   = $this->params->get('main_addattr', '');
$main_addattr                   = (strlen($main_addattr) > 0) ? ' ' . $main_addattr : '';
$main_addattr_container         = $this->params->get('main_addattr_container', '');
$main_addattr_container         = (strlen($main_addattr_container) > 0) ? ' ' . $main_addattr_container : '';
$main_addclasses_container      = $this->params->get('main_addclasses_container', '');
$main_addclasses_container      = (strlen($main_addclasses_container) > 0) ? ' ' . $main_addclasses_container : '';
$sections                       = [];
$sections['sb-main']['isExist'] = 1;
$positions                      = (array) $this->params->get('positions-location');
if (is_array($positions) && count($positions) > 0) {

    foreach ($positions as $posid => $position) {
        $position                                         = (array) $position;
        $sections[strtolower($position['pos-section'])][] = $position;
        $sections[$position['pos-section']]['isExist']    = (isset($sections[$position['pos-section']]['isExist'])) ? $sections[$position['pos-section']]['isExist'] : 0;
        if ((strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name']) > 0) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-left')) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-right')) || (strlen($position['pos-name']) > 0 && $this->countModules($position['pos-name'] . '-center'))) {
            $sections[strtolower($position['pos-section'])]['isExist'] = 1;
        }
    }
}
//Sidebars
$sb1_show       = $this->params->get('sidebar-a_show');
$sb1_tag        = $this->params->get('sidebar-a_tag');
$sb1_position   = $this->params->get('sidebar-a_position');
$sb1_width      = ($sb1_show) ? $this->params->get('sidebar-a_width') : 0;
$sb1_height     = ($sb1_show) ? $this->params->get('sidebar-a_height') : 1;
$sb1_addClass   = ($sb1_show) ? ' ' . $this->params->get('sidebar-a_addclasses') : '';
$sb1_addAttr    = ($sb1_show) ? ' ' . $this->params->get('sidebar-a_addattr') : '';
$sb1_real_width = 0; // Учитывает ширину, только, если сайдбар заполнен

$sb2_show       = $this->params->get('sidebar-b_show');
$sb2_tag        = $this->params->get('sidebar-b_tag');
$sb2_position   = $this->params->get('sidebar-b_position');
$sb2_width      = ($sb2_show) ? $this->params->get('sidebar-b_width') : 0;
$sb2_height     = ($sb1_show) ? $this->params->get('sidebar-b_height') : 1;
$sb2_addClass   = ($sb2_show) ? ' ' . $this->params->get('sidebar-b_addclasses') : '';
$sb2_addAttr    = ($sb2_show) ? ' ' . $this->params->get('sidebar-b_addattr') : '';
$sb2_real_width = 0; // Учитывает ширину, только, если сайдбар заполнен

$sb1_main_show       = $this->params->get('main-sidebar-a_show');
$sb1_main_position   = $this->params->get('main-sidebar-a_position');
$sb1_main_width      = ($sb1_main_show) ? $this->params->get('main-sidebar-a_width') : 0;
$sb1_main_height     = ($sb1_main_show) ? $this->params->get('main-sidebar-a_height') : 1;
$sb1_main_real_width = 0; // Учитывает ширину, только, если сайдбар заполнен

$sb2_main_show             = $this->params->get('main-sidebar-b_show');
$sb2_main_position         = $this->params->get('main-sidebar-b_position');
$sb2_main_width            = ($sb2_main_show) ? $this->params->get('main-sidebar-b_width') : 0;
$sb2_main_height           = ($sb2_main_show) ? $this->params->get('main-sidebar-b_height') : 1;
$sb2_main_real_width       = 0; // Учитывает ширину, только, если сайдбар заполнен
// Off-canvases
$offcanvas1_show           = $this->params->get('off-canvas-a_show');
$offcanvas1_tag            = $this->params->get('off-canvas-a_tag');
$offcanvas1_position       = $this->params->get('off-canvas-a_position');
$offcanvas1_animation      = $this->params->get('off-canvas-a_animation');
$offcanvas1_flip           = ($offcanvas1_position == '1') ? 'false' : 'true';
$offcanvas1_overlay        = $this->params->get('off-canvas-a_overlay');
$offcanvas1_close          = $this->params->get('off-canvas-a_close_button');
$offcanvas1_close_large    = $this->params->get('off-canvas-a_close_button_large');
$offcanvas1_addclasses     = $this->params->get('off-canvas-a_addclasses');
$offcanvas1_addattr        = $this->params->get('off-canvas-a_addattr');
$offcanvas1_bar_addclasses = $this->params->get('off-canvas-a_bar_addclasses');
$offcanvas1_bar_addattr    = $this->params->get('off-canvas-a_bar_addattr');
$offcanvas2_show           = $this->params->get('off-canvas-b_show');
$offcanvas2_tag            = $this->params->get('off-canvas-b_tag');
$offcanvas2_position       = $this->params->get('off-canvas-b_position');
$offcanvas2_animation      = $this->params->get('off-canvas-ab_animation');
$offcanvas2_flip           = ($offcanvas1_position == '1') ? 'false' : 'true';
$offcanvas2_overlay        = $this->params->get('off-canvas-b_overlay');
$offcanvas2_close          = $this->params->get('off-canvas-b_close_button');
$offcanvas2_close_large    = $this->params->get('off-canvas-b_close_button_large');
$offcanvas2_addclasses     = $this->params->get('off-canvas-b_addclasses');
$offcanvas2_addattr        = $this->params->get('off-canvas-b_addattr');
$offcanvas2_bar_addclasses = $this->params->get('off-canvas-b_bar_addclasses');
$offcanvas2_bar_addattr    = $this->params->get('off-canvas-b_bar_addattr');

//Less
$less_acompile = $this->params->get('less_acompile', 0);
//Remove Bootstrap
$disable_bs    = $this->params->get('killbootstrap', 1);
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
if ((int) Version::MAJOR_VERSION < 4) {

// Load jQuery
    JHtml::_('jquery.framework');
}

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
    $css_path = JPATH_THEMES . '/' . $this->template . '/css/';
    $excluded = explode(',', $this->params->get('css_exclude_files', ''));
    $template = 0;
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
                                    $ext1 = (explode('.', $file1));
                                    $ext1 = end($ext1);
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
                if (file_exists($css_path . '/template.min.css')) {
                    $doc->addStyleSheet($tplpath . '/css/template.min.css');
                } else {
                    $doc->addStyleSheet($tplpath . '/css/template.css');
                }
            }
            closedir($dh);
        }
    }
}

function _buildPosition($template, $posName, $params, $sections) {
    $posName       = strtolower($posName);
    $suffix        = str_replace('sb-', '', $posName);
    $section_class = $suffix;
    $section_class .= isset($params[$suffix . '_addclasses']) ? ' ' . $params[$suffix . '_addclasses'] : '';
    $section_class .= isset($params[$suffix . '_color']) ? ' uk-section-' . $params[$suffix . '_color'] : '';
    $section_attr  = isset($params[$suffix . '_addattr']) ? ' ' . $params[$suffix . '_addattr'] : '';
    $section_tag   = isset($params[$suffix . '_tag']) ? $params[$suffix . '_tag'] : 'section';
    if (isset($params[$suffix . '_size'])) {
        switch ($params[$suffix . '_size']) {
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

    if (isset($params[$suffix . '_container']) && $params[$suffix . '_container'] !== '0') {
        $out .= '<div class="uk-container';
        if ($params[$suffix . '_container'] == '1') {
            $out .= ' uk-container-center';
        }
        switch ($params[$suffix . '_container_width']) {
            case 'max':
                break;
            default:
                $out .= ' uk-container-' . $params[$suffix . '_container_width'];
                break;
        }
        $out .= '">';
    }
    if (isset($sections[$posName]) || isset($sections[$posName . '-left']) || isset($sections[$posName . '-right']) || isset($sections[$posName . '-center'])) {
        foreach ($sections[$posName] as $section_item) {
            if (is_array($section_item)) {
                $pos_name = strtolower($section_item["pos-name"]);
                if ($template->countModules($pos_name) && isset($section_item['pos-container']) && $section_item['pos-container'] > 0) {
                    $out .= '<div class="uk-container';
                    if ($section_item['pos-container'] == 1) {
                        $out .= ' uk-container-center';
                    }
                    $out .= ' uk-container-' . $section_item['pos-container_width'];
                    $out .= strlen(trim($section_item['pos-container-addclasses'])) > 0 ? ' ' . trim($section_item['pos-container-addclasses']) : '';
                    $out .= '"';
                    $out .= strlen(trim($section_item['pos-container-addparams'])) > 0 ? ' ' . trim($section_item['pos-container-addparams']) : '';
                    $out .= '>';
                }
                if ($template->countModules($section_item["pos-name"]) ||
                        (
                        isset($section_item['pos-navbar']) && ($template->countModules($section_item["pos-name"] . '-left') ||
                        $template->countModules($section_item["pos-name"] . '-center') ||
                        $template->countModules($section_item["pos-name"] . '-right') )
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
                        $out         .= '<div id="' . $section_item['pos-name'] . '-modal" uk-modal="' . $section_item['pos-modal-params'] . '" class="' . $modal_class . '" ' . $section_item['pos-modal-addparams-modal'] . '>';
                        $out         .= '<div class="' . $section_item['pos-modal-addclasses-dialog'] . '" ' . $section_item['pos-modal-addparams-dialog'] . '>';
                        if ($section_item['pos-modal-close'] > 0) {
                            $close_class = '';
                            $close_class .= "uk-modal-close-" . $section_item['pos-modal-close-pos'];
                            $close_class .= ($section_item['pos-modal-close-size'] != 'default') ? " uk-close-" . $section_item['pos-modal-close-size'] : "";
                            $close_tag   = ($section_item['pos-modal-close-tag'] == 'a') ? 'a href=""' : 'button type="button"';
                            $out         .= '<' . $close_tag . ' uk-close class="' . $close_class . '"></' . $section_item['pos-modal-close-tag'] . '>';
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
                        if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1') {
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
                    if ($template->countModules($pos_name) && isset($section_item['pos-container']) && $section_item['pos-container'] > 0) {
                        $out .= '</div>';
                    }
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
    if (isset($params[$suffix . '_container']) && $params[$suffix . '_container'] !== '0') {
        $out .= '</div>';
    }
    $out .= '</' . $section_tag . '>';
    return $out;
}
