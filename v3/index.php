<?php
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

include_once JPATH_THEMES . '/' . $this->template . '/includes/config.php';
if (!file_exists(JPATH_THEMES . '/' . $this->template . '/themes/')) {
	?>
	<div uk-alert class="uk-alert-warning uk-text-center">
		<?php
		echo Text::_('TPL_SIMPLE_BLANK_THEME_EXISTS_ALERT');
		?>
	</div>
	<?php
}
if (isset($_POST['page_title'])) {
	$doc = JFactory::getDocument();
	$doc->setTitle($_POST['page_title']);
}

$sb1_exist					 = $sb1_show == 1 && ($this->countModules('sb-sidebar-a') || (isset($sections['sb-sidebar-a']) && $sections['sb-sidebar-a']['isExist'] > 0));
$sb2_exist					 = $sb2_show == 1 && ($this->countModules('sb-sidebar-b') || (isset($sections['sb-sidebar-b']) && $sections['sb-sidebar-b']['isExist'] > 0));
$sb1_main_exist				 = $sb1_main_show == 1 && ($this->countModules('sb-main-sidebar-a') || (isset($sections['sb-main-sidebar-a']) && $sections['sb-main-sidebar-a']['isExist'] > 0));
$sb2_main_exist				 = $sb2_main_show == 1 && ($this->countModules('sb-main-sidebar-b') || (isset($sections['sb-main-sidebar-b']) && $sections['sb-main-sidebar-b']['isExist'] > 0));
$sb1_real_width				 = ($sb1_exist) ? $sb1_width : $sb1_real_width;
$sb2_real_width				 = ($sb2_exist) ? $sb2_width : $sb2_real_width;
$sb1_main_real_width		 = ($sb1_main_exist) ? $sb1_main_width : $sb1_main_real_width;
$sb2_main_real_width		 = ($sb2_main_exist) ? $sb2_main_width : $sb2_main_real_width;
$content_width				 = $fraction(60 - $sb1_real_width - $sb2_real_width);
$content_width_array		 = explode('-', $content_width);
$main_content_width			 = $fraction(60 - $sb1_main_real_width - $sb2_main_real_width);
$main_content_width_array	 = explode('-', $main_content_width);

function extParams($tplparams, $param, $value) {
	if (is_array($param)) {
		foreach ($param as $par) {
			$tplparams[$par] .= ($tplparams[$par] === '') ? $value : ' ' . $value;
		}
	} else {
		$tplparams[$param] .= ($tplparams[$param] === '') ? $value : ' ' . $value;
	}
	return $tplparams;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" class="">

	<head>
		<?php
		if (file_exists(JPATH_THEMES . '/' . $this->template . '/includes/head.php')) {
			include JPATH_THEMES . '/' . $this->template . '/includes/head.php';
		}
		?>
	<jdoc:include type="head" />
</head>

<body class="sb-<?php echo $view; ?><?php echo ' ' . $pageclass; ?><?php echo $bodyflex; ?>" role="document"<?php echo $bodyfullheight; ?>>
	<?php
	// Wrappers
	if (isset($wrappersenable) && $wrappersenable > 0) {
		for ($i = 1; $i <= $wrappersenable; $i++) {
			?>
			<div id="sb-content-wrapper-<?php echo $i; ?>" class="sb-content-wrapper-<?php echo $i; ?>">
				<?php
			}
		}
		if ($sb1_exist || $sb2_exist) {
			?>
	<!-- grid-<?php echo $sb1_height . '-' . $sb2_height; ?> pattern included -->
			<?php
			include_once JPATH_THEMES . '/simple_blank/includes/patterns/grid-' . $sb1_height . '-' . $sb2_height . '.php';
		} else {
			include_once JPATH_THEMES . '/simple_blank/includes/sections/top.php';
			include_once JPATH_THEMES . '/simple_blank/includes/sections/main.php';
			include_once JPATH_THEMES . '/simple_blank/includes/sections/bottom.php';
		}
		include_once JPATH_THEMES . '/simple_blank/includes/sections/offcanvas.php';
		?>
		<?php
		if (file_exists(JPATH_THEMES . '/' . $this->template . '/includes/footer.php')) {
			include JPATH_THEMES . '/' . $this->template . '/includes/footer.php';
		}
		?>
		<jdoc:include type="modules" name="sb-debug" />

		<?php
		include JPATH_THEMES . '/' . $this->template . '/includes/analytics.php';
		?>
		<?php
		if (isset($wrappersenable) && $wrappersenable > 0) {
			for ($i = 1; $i < $wrappersenable; $i++) {
				?>
			</div>
			<?php
		}
	}
	?>
	<?php
	if ($qlenable == 1) {
		?>
		<script>
			window.addEventListener('load', () => {
				quicklink.listen();
			});
		</script>
		<?php
	}
	?>
</body>

</html>
