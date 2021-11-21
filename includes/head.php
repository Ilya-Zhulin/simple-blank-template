<?php
// no direct access
defined('_JEXEC') or die;
//Less
$less_acompile	 = $this->params->get('less_acompile', 0);
$favicon_mode	 = $this->params->get('favicon_mode', 0);
$qlenable		 = $this->params->get('qlenable', 1);
//$less_custom_files	 = $this->params->get('less_custom_file', 'template.css');
//$less_custom_files	 = (strpos($less_custom_files, ',') !== FALSE) ? explode(',', $less_custom_files) : $less_custom_files;
?>

<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta http-equiv="cleartype" content="on">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="HandheldFriendly" content="true">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<?php
if (file_exists(JPATH_ROOT . '/templates/simple_blank/themes/lproject/head_top.php')) {
	include_once JPATH_ROOT . '/templates/simple_blank/themes/lproject/head_top.php';
}
if ($favicon_mode == 0 && JFile::exists(JPATH_ROOT . '/templates/simple_blank/images/favicon/favicon.ico')) {
	?>
	<link rel="icon" href="/templates/<?php echo $this->template ?>/images/favicon/favicon.svg">
	<link rel="apple-touch-icon" sizes="180x180" href="/templates/<?php echo $this->template ?>/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/templates/<?php echo $this->template ?>/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/templates/<?php echo $this->template ?>/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/templates/<?php echo $this->template ?>/images/favicon/site.webmanifest">
	<link rel="mask-icon" href="/templates/<?php echo $this->template ?>/images/favicon/safari-pinned-tab.svg" color="#000000">
	<meta name="msapplication-TileColor" content="#ada8a7">
	<meta name="msapplication-TileImage" content="/templates/<?php echo $this->template ?>/images/favicon/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<?php
} else {
	?>
	<link rel="icon" href="/templates/<?php echo $this->template ?>/images/template_favicon/favicon.svg">
	<link rel="apple-touch-icon" sizes="180x180" href="/templates/<?php echo $this->template ?>/images/template_favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/templates/<?php echo $this->template ?>/images/template_favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/templates/<?php echo $this->template ?>/images/template_favicon/favicon-16x16.png">
	<link rel="manifest" href="/templates/<?php echo $this->template ?>/images/template_favicon/site.webmanifest">
	<link rel="mask-icon" href="/templates/<?php echo $this->template ?>/images/template_favicon/safari-pinned-tab.svg" color="#000000">
	<meta name="msapplication-TileColor" content="#ada8a7">
	<meta name="msapplication-TileImage" content="/templates/<?php echo $this->template ?>/images/template_favicon/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">
<?php }
?>
<?php
if ($qlenable == 1) {
	?>
	<script src = "/templates/simple_blank/js/quicklink.js"></script>
	<script>
		window.addEventListener('load', () => {
			quicklink.listen();
		});
	</script>
	<?php
}
?>
<?php
if ($less_acompile == 1) {
	$less_path = '/templates/' . $this->template . '/less/';
	?>
	<link rel="stylesheet/less" href="<?php echo $less_path; ?>template.less" />
	<script src="templates/<?php echo $this->template ?>/vendor/LessJS/less.min.js" ></script>
	<?php
}
?>
<?php
if (file_exists(JPATH_ROOT . '/templates/simple_blank/themes/lproject/head_bottom.php')) {
	include_once JPATH_ROOT . '/templates/simple_blank/themes/lproject/head_bottom.php';
}
?>