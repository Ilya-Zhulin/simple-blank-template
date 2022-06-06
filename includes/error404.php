<?php
/*
 * Simple Blank Template
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2021
 *
 * Don't change this file!
 * Copy it to your theme for overriding
 */

defined('_JEXEC') or die('Restricted access');

$app	 = JFactory::getApplication();
$user	 = JFactory::getUser();
$doc	 = JFactory::getDocument();

// Getting params from template
$params	 = $app->getTemplate(true)->params;
$tplpath = $this->baseurl . '/templates/' . $this->template;

// Detecting Active Variables
$option		 = $app->input->getCmd('option', '');
$view		 = $app->input->getCmd('view', '');
$layout		 = $app->input->getCmd('layout', '');
$task		 = $app->input->getCmd('task', '');
$itemid		 = $app->input->getCmd('Itemid', '');
$format		 = $app->input->getCmd('format', 'html');
$sitename	 = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$doc->addScript($tplpath . '/vendor/uikit/js/uikit.min.js');
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta http-equiv="cleartype" content="on">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="HandheldFriendly" content="true">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="default">
		<title><?php echo $this->title; ?> - <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></title>
		<link rel="icon" href="/templates/<?php echo $this->template ?>/images/template_favicon/favicon.svg">
		<link rel="apple-touch-icon" sizes="180x180" href="/templates/<?php echo $this->template ?>/images/template_favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/templates/<?php echo $this->template ?>/images/template_favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/templates/<?php echo $this->template ?>/images/template_favicon/favicon-16x16.png">
		<link rel="manifest" href="/templates/<?php echo $this->template ?>/images/template_favicon/site.webmanifest">
		<link rel="mask-icon" href="/templates/<?php echo $this->template ?>/images/template_favicon/safari-pinned-tab.svg" color="#000000">
		<meta name="msapplication-TileColor" content="#ada8a7">
		<meta name="msapplication-TileImage" content="/templates/<?php echo $this->template ?>/images/template_favicon/mstile-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<script src="<?php echo $tplpath; ?>/vendor/uikit/js/uikit.min.js" ></script>
		<link href="<?php echo $tplpath; ?>/css/template.css" rel="stylesheet">
	</head>
	<body uk-height-viewport="expand: true" class="uk-flex uk-flex-center uk-flex-middle">
		<div id="center-container" class="uk-text-center">
			<img src="/templates/<?php echo $this->template ?>/images/template_thumbnail.svg" alt="Simple Blank Template Logo"  class="" />
			<h1 style="margin: 0!important; margin-top: 50px!important; padding: 0!important;">
				<?php echo $this->title; ?> - <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
			</h1>
		</div>
		<script>
            document.addEventListener("DOMContentLoaded",()=>{var a=1*document.body.style["min-height"].replace("px","");let b=document.body.getBoundingClientRect().height;a/=b;1>a&&(document.querySelector("#center-container img").style.height=.75*(a*document.querySelector("#center-container").getBoundingClientRect().height-document.querySelector("h1").getBoundingClientRect().height-50)+"px")});
		</script>
</html>