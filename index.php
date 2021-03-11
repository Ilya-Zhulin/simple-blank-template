<?php
// no direct access
defined('_JEXEC') or die;
include JPATH_THEMES . '/' . $this->template . '/includes/config.php';

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
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language;
?>" dir="<?php echo $this->direction; ?>" class="">

	<head>
		<?php include JPATH_THEMES . '/' . $this->template . '/includes/head.php'; ?>
	<jdoc:include type="head" />
</head>

<body class="sb-<?php echo $view; ?><?php echo ' ' . $pageclass; ?>" role="document"<?php echo $bodyfullheight; ?>>
	<?php
	if (isset($wrappersenable) && $wrappersenable > 0) {
		for ($i = 1; $i <= $wrappersenable; $i++) {
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
		// Считаем позиции вo внутренних секциях
		$inner_section_count = 0;
		foreach ($sb_inner_sections_array as $sb_inner_sections_item) {
			if ($this->countModules($sb_inner_sections_item) || (isset($sections[$sb_inner_sections_item]) && $sections[$sb_inner_sections_item]['isExist'] > 0)) {
				$inner_section_count += 1;
			}
		}
		if ($inner_section_count > 0 || $hidecomponent == 1) {
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
					$out .= $addclasses_container_main;
					$out .= '"';
					$out .= $addattr_container_main;
					$out .= '>';
				}
				echo $out;
				if ($grid_main > 0) {
					?>
					<div uk-grid<?php echo $grid_attr_main . $grid_classes_main; ?>>
						<?php
					}
					if ($sb1_show == 1 && ($this->countModules('sb-sidebar-a') || (isset($sections['sb-sidebar-a']) && $sections['sb-sidebar-a']['isExist'] > 0))) {
						$sb1_real_width = $sb1_width;
						if ($sb1_position == '1') {
							?>
							<aside id="sb-sidebar-a" class="sidebar-a uk-width-<?php echo $fraction($sb1_width); ?>@m" role="complementary">
								<jdoc:include type="modules" name="sidebar-a" style=""/>
								<?php
								foreach ($sections['sb-sidebar-a'] as $sb_sidebar_a_position) {
									if ($this->countModules($sb_sidebar_a_position['pos-name'])) {
										echo _buildPosition($this, $sb_sidebar_a_position['pos-name'], $tplparams, $sections);
									}
								}
								?>
							</aside>
							<?php
						}
					}
					if ($sb2_show == 1 && ($this->countModules('sb-sidebar-b') || (isset($sections['sb-sidebar-b']) && $sections['sb-sidebar-b']['isExist'] > 0))) {
						$sb2_real_width = $sb2_width;
						if ($sb2_position == '1') {
							?>
							<aside id="sb-sidebar-b" class="sidebar-b uk-width-<?php echo $fraction($sb1_width); ?>@m" role="complementary">
								<jdoc:include type="modules" name="sidebar-b" style=""/>
								<?php
								foreach ($sections['sb-sidebar-b'] as $sb_sidebar_b_position) {
									if ($this->countModules($sb_sidebar_a_position['pos-name'])) {
										echo _buildPosition($this, $sb_sidebar_a_position['pos-name'], $tplparams, $sections);
									}
								}
								?>
							</aside>
							<?php
						}
					}
					?>
					<?php
					if ($hidecomponent == 1) {
						$content_width		 = $fraction(60 - $sb1_real_width - $sb2_real_width);
//					JBDump(60 - $sb1_width - $sb2_width, 0);
						$content_width_array = explode('-', $content_width);
						if ($content_width_array[1] > 6) {
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
					if ($sb1_show == 1 && ($this->countModules('sb-sidebar-a') || (isset($sections['sb-sidebar-a']) && $sections['sb-sidebar-a']['isExist'] > 0)) && $sb1_position == '0') {
						$sb1_real_width = $sb1_width;
						?>
						<aside id="sb-sidebar-a" class="sidebar-a uk-width-<?php echo $fraction($sb1_width); ?>@m" role="complementary">
							<jdoc:include type="modules" name="sidebar-a" style=""/>
							<?php
							foreach ($sections['sb-sidebar-a'] as $sb_sidebar_a_position) {
								if ($this->countModules($sb_sidebar_a_position['pos-name'])) {
									echo _buildPosition($this, $sb_sidebar_a_position['pos-name'], $tplparams, $sections);
								}
							}
							?>
						</aside>
						<?php
					}
					if ($sb2_show == 1 && ($this->countModules('sb-sidebar-b') || (isset($sections['sb-sidebar-b']) && $sections['sb-sidebar-b']['isExist'] > 0)) && $sb2_position == '0') {
						$sb2_real_width = $sb2_width;
						?>
						<aside id="sb-sidebar-b" class="sidebar-b uk-width-<?php echo $fraction($sb1_width); ?>@m" role="complementary">
							<jdoc:include type="modules" name="sidebar-b" style=""/>
							<?php
							foreach ($sections['sb-sidebar-b'] as $sb_sidebar_b_position) {
								if ($this->countModules($sb_sidebar_a_position['pos-name'])) {
									echo _buildPosition($this, $sb_sidebar_a_position['pos-name'], $tplparams, $sections);
								}
							}
							?>
						</aside>
						<?php
					}
					if ($grid_main > 0) {
						?>
					</div>
					<?php
				}
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
		<?php
		include JPATH_THEMES . '/' . $this->template . '/includes/footer.php';
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
	<script>
		window.addEventListener('load', () =>{
			quicklink.listen();
		});
	</script>
</body>

</html>
