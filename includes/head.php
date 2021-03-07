<?php
// no direct access
defined('_JEXEC') or die;
//Less
$less_acompile = $this->params->get('less_acompile', 0);
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
<link rel="icon" href="/images/favicon/favicon.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
<link rel="manifest" href="/images/favicon/site.webmanifest">
<link rel="mask-icon" href="/images/favicon/safari-pinned-tab.svg" color="#000000">
<meta name="msapplication-TileColor" content="#ada8a7">
<meta name="msapplication-TileImage" content="/images/favicon/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">
<script src="/js/quicklink.js"></script>
<?php
if ($less_acompile == 1) {
	$less_path = '/templates/' . $this->template . '/less/';
	?>
	<link rel="stylesheet/less" href="<?php echo $less_path; ?>template.less" />
	<script src="templates/<?php echo $this->template ?>/vendor/LessJS/less.min.js" ></script>
	<?php
}
?>