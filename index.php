<?php
// no direct access
defined('_JEXEC') or die;
include JPATH_THEMES . '/' . $this->template . '/includes/config.php';
include JPATH_THEMES . '/' . $this->template . '/scripts/vars.php';

$gcf = function($a, $b = 60) use(&$gcf) {

	return (int) ($b > 0 ? $gcf($b, $a % $b) : $a);
};

$fraction = function($nominator, $divider = 60) use(&$gcf) {
	return $nominator / ($factor = $gcf($nominator, $divider)) . '-' . $divider / $factor;
};
if (isset($_POST['page_title'])) {
	$doc = JFactory::getDocument();
	$doc->setTitle($_POST['page_title']);
}



/**
 * контроль доступа пользователя
 */
$user = JCckUser::getUser();
if ($user->guest == 0) {
	switch ($user->user_status) {
		case '1':  // Педагог
			if (!in_array('10', $user->groups)) {
				JUserHelper::setUserGroups($user->id, array(10));
			}
			$code = $user->user_secret_code;
			JCckDatabase::doQuery("update #__teachers set userid=$user->id where code=$code");
			break;
		case '2':  // Студент
			if (!in_array('11', $user->groups)) {
				JUserHelper::setUserGroups($user->id, array(11));
			}
			break;
	}
}
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" class="">

    <head>
		<?php include JPATH_THEMES . '/' . $this->template . '/includes/head.php'; ?>
    <jdoc:include type="head" />
</head>

<body class="<?php echo $pageclass; ?> sb-<?php echo $view; ?>" role="document">
	<?php
	if ($wrappersenabled > 0) {
		for ($i = 1; $i <= $wrappersenabled; $i++) {
			?>
			<div id="sb-content-wrapper-<?php echo $i; ?>" class="sb-content-wrapper-<?php echo $i; ?>">
				<?php
			}
		}
		?>

		<?php
		foreach ($sb_top_sections_array as $sb_top_sections_item) {
			if ($this->countModules($sb_top_sections_item) || (isset($sections[$sb_top_sections_item]) && $sections[$sb_top_sections_item]['isExist'] > 0)) {
				echo _buildPosition($this, $sb_top_sections_item, $tplparams, $sections);
			}
		}
		?>
		<?php
		if ($this->countModules('sidebar-a + sidebar-b') || $hidecomponent == 1) {
			?>
			<section id="sb-main" <?php echo $addclasses_main . $addattr_main; ?>>

				<?php
				$out = '';
				if (isset($container_main) && $container_main !== '0') {
					$out .= '<div class="uk-container';
					if ($tplparams['container_main'] == '1') {
						$out .= ' uk-container-center';
					}
					switch ($tplparams['container_width_main']) {
						case 'max':
							break;
						default:
							$out .= ' uk-container-' . $container_width_main;
							break;
					}
					$out .= '">';
				}
				echo $out;
				if ($sb1_show == 1 && $this->countModules('sidebar-a') && $sb1_position == '1'):
					$sb1_real_width = $sb1_width;
					?>
					<aside id="sb-sidebar-a" class="sidebar-a uk-width-medium-<?php echo $fraction($sb1_width); ?>" role="complementary">
						<jdoc:include type="modules" name="sidebar-a" style=""/>
					</aside>
				<?php endif; ?>

				<?php
				if ($sb2_show == 1 && $this->countModules('sidebar-b') && $sb2_position == '1'):
					$sb2_real_width = $sb2_width;
					?>
					<aside id="sb-sidebar-b" class="sidebar-b uk-width-medium-<?php echo $fraction($sb2_width); ?>" role="complementary">
						<jdoc:include type="modules" name="sidebar-b" style=""/>
					</aside>
				<?php endif; ?>

				<?php
				if ($hidecomponent == 1) {
					$content_width		 = $fraction(60 - $sb2_real_width - $sb2_real_width);
					$content_width_array = explode('-', $content_width);
					if ($content_width_array[1] > 10) {
						echo '<div class = "uk-width-1-1"><div class = "uk-alert uk-alert-warning"><i class = "uk-icon-exclamation-triangle uk-icon-small"></i> Sorry, content width class (uk-width-medium-' . $content_width . ') is not supported. Please change sidebar width to show correct grid.</div></div>';
					} else {
						?>
						<main id="sb-content" class="uk-width-<?php echo $content_width; ?>" role="main">
							<?php
							if ($this->countModules('sb-main-top') || (isset($sections['sb-main-top']) && $sections['sb-main-top']['isExist'] > 0)) {
								echo _buildPosition($this, 'sb-main-top', $tplparams, $sections);
							}
							echo '<jdoc:include type="message" />';
							echo '<jdoc:include type="component" />';
							if ($this->countModules('sb-main-bottom') || (isset($sections['sb-main-bottom']) && $sections['sb-main-bottom']['isExist'] > 0)) {
								echo _buildPosition($this, 'sb-main-bottom', $tplparams, $sections);
							}
							?>
						</main>
						<?php
					}
				}
				?>


				<?php if ($sb1_show == 1 && $this->countModules('sidebar-a') && $sb1_position == '0'): ?>
					<aside id="sidebar-a" class="sidebar-a uk-width-medium-<?php echo $fraction($sb1_width); ?>" role="complementary">
						<jdoc:include type="modules" name="sidebar-a" style=""/>
					</aside>
				<?php endif; ?>

				<?php if ($sb2_show == 1 && $this->countModules('sidebar-b') && $sb2_position == '0'): ?>
					<aside id="sidebar-b" class="sidebar-b uk-width-medium-<?php echo $fraction($sb2_width); ?>" role="complementary">
						<jdoc:include type="modules" name="sidebar-b" style=""/>
					</aside>
				<?php endif; ?>
				<?php
				if (isset($tplparams['container_main']) && $tplparams['container_main'] !== '0') {
					echo '</div>';
				}
				?>
			</section>
			<?php
		}
		foreach ($sb_bottom_sections_array as $sb_bottom_sections_item) {
			if ($this->countModules($sb_bottom_sections_item) || (isset($sections[$sb_bottom_sections_item]) && $sections[$sb_bottom_sections_item]['isExist'] > 0)) {
				echo _buildPosition($this, $sb_bottom_sections_item, $tplparams, $sections);
			}
		}
		foreach ($sb_offcanvas_array as $sb_offcanvas_item) {
			if ($this->countModules($sb_offcanvas_item) || (isset($sections[$sb_offcanvas_item]) && $sections[$sb_offcanvas_item]['isExist'] > 0)) {
				?>
				<div id="<?php echo $sb_offcanvas_item ?>-wrapper" uk-offcanvas="mode: <?php echo $offcanvas1_animation; ?>; overlay: true; flip: <?php echo $offcanvas1_flip; ?>">
					<div class="uk-offcanvas-bar">
						<?php
						echo _buildPosition($this, $sb_offcanvas_item, $tplparams, $sections);
						?>
					</div>
				</div>
				<?php
			}
		}
		?>

        <jdoc:include type="modules" name="sb-debug" />

		<?php
		include JPATH_THEMES . '/' . $this->template . '/includes/analytics.php';
		?>
		<?php
		if ($wrappersenabled > 0) {
			for ($i = 1; $i < $wrappersenabled; $i++) {
				?>
			</div>
			<?php
		}
	}
	?>
    <script src="//instant.page/3.0.0" type="module" defer integrity="sha384-OeDn4XE77tdHo8pGtE1apMPmAipjoxUQ++eeJa6EtJCfHlvijigWiJpD7VDPWXV1"></script>
</body>

</html>
