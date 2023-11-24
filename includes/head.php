<?php
// no direct access
defined('_JEXEC') or die;
define('DS', DIRECTORY_SEPARATOR);

use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

$tmpl_path		 = JPATH_THEMES . DIRECTORY_SEPARATOR . $this->template;
$tmpl_img_path	 = $tmpl_path . DS . 'images';
$theme_name		 = $this->params->get('theme_select', '');
//Less
$less_acompile	 = $this->params->get('less_acompile', 0);
$favicon_mode	 = $this->params->get('favicon_mode', 0);
//$less_custom_files	 = $this->params->get('less_custom_file', 'template.css');
//$less_custom_files	 = (strpos($less_custom_files, ',') !== FALSE) ? explode(',', $less_custom_files) : $less_custom_files;
?>

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="HandheldFriendly" content="true">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<?php
if (file_exists($tmpl_path . DS . 'themes' . DS . $theme_name . DS . 'head_top.php')) {
	include_once $tmpl_path . DS . 'themes' . DS . $theme_name . DS . 'head_top.php';
}
$files			 = Folder::files($tmpl_img_path . DS . 'template_favicon');
$favicon_path	 = JPATH_ROOT; // Если всё стереть, будет искать favicon в корне сайта
if ($favicon_mode == 1) { // favicon берётся из шаблона
	if (File::exists($tmpl_img_path . DS . 'favicon' . DS . 'favicon.ico')) {
		$favicon_path = $tmpl_img_path . DS . 'favicon';
	} elseif (File::exists($tmpl_img_path . DS . 'template_favicon' . DS . 'favicon.ico')) {
		$favicon_path = $tmpl_img_path . DS . 'template_favicon';
	}
} elseif ($favicon_mode == 0 && isset($theme_name) && strlen($theme_name) > 0) {// favicon берётся из темы
	if (Folder::exists($tmpl_path . DS . 'themes' . DS . $theme_name)) {
		$favicon_path = $tmpl_path . DS . 'themes' . DS . $theme_name . DS . 'images' . DS . 'favicon';
	}
}
if (File::exists($favicon_path . DS . 'favicon.ico')) { //если нет иконок, то нечего и выводить
	$favicon_url	 = str_replace([JPATH_ROOT, '\\'], ['', '/'], $favicon_path);
	$favicon_html	 = '';
	$icons			 = Folder::files($favicon_path);
	foreach ($icons as $icon) {
		$ext = File::getExt($icon);
		switch ($ext) {
			case 'svg':
				$favicon_html	 .= '<link rel = "icon" href = "' . $favicon_url . '/' . $icon . '" type = "image/svg+xml">';
				break;
			case 'ico':
				$sizes			 = 'any';
				preg_match('/\d*x\d*/', $icon, $sizes_ar);
				if (count($sizes_ar) > 0) {
					$sizes = $sizes_ar[0];
				}
				$favicon_html	 .= '<link rel = "icon" href = "' . $favicon_url . '/' . $icon . '" sizes = "' . $sizes . '">';
				break;
			case 'webmanifest':
				$favicon_html	 .= '<link rel = "manifest" href = "' . $favicon_url . '/' . $icon . '">';
				break;
			case 'png':
				$sizes			 = 'any';
				preg_match('/\d*x\d*/', $icon, $sizes_ar);
				if (count($sizes_ar) > 0) {
					$sizes = $sizes_ar[0];
				}
				$icon_name_ar = explode('-', $icon);
				switch ($icon_name_ar[0]) {
					case 'android':
						// подключается в манифесте
						break;
					case 'apple':
						$favicon_html	 .= '<link rel = "apple-touch-icon" href = "' . $favicon_url . '/' . $icon . '" sizes = "' . $sizes . '">';
						break;
					case 'apple':
						$favicon_html	 .= '<link rel = "apple-touch-icon" href = "' . $favicon_url . '/' . $icon . '" sizes = "' . $sizes . '">';
						break;
					case 'favicon':
						$favicon_html	 .= '<link rel = "icon" type = "image/png" href = "' . $favicon_url . '/' . $icon . '" sizes = "' . $sizes . '">';
						break;
					case 'safari':
						// deprecated. Не используется в современных браузерах и системах. Даже на apple.com нету
						$favicon_html	 .= '<link rel = "mask-icon" href = "' . $favicon_url . '/' . $icon . '" sizes = "' . $sizes . '">';
						break;
				}
				break;
		}
	}
	echo $favicon_html;
	/*
	 * Остальные иконки считаются устаревшими. Если требуется из подключить, то можно подключить в head_bottom.php вашей темы.
	 */
}
?>
<?php
if ($qlenable == 1) {
	?>
	<script src = "/templates/<?php echo $this->template ?>/js/quicklink.jsjs/quicklink.js"></script>
	<?php
}
?>
<?php
if ($less_acompile == 1) {
	$less_path = DS . 'templates/' . $this->template . DS . 'less/';
	?>
	<link rel="stylesheet/less" href="<?php echo $less_path; ?>template.less" />
	<script src="templates/<?php echo $this->template ?>/vendor/LessJS/less.min.js" ></script>
	<?php
}
if (file_exists($tmpl_path . DS . 'themes' . DS . $theme_name . DS . 'head_bottom.php')) {
	include_once $tmpl_path . DS . 'themes' . DS . $theme_name . DS . 'head_bottom.php';
}
?>