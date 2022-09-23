<?php
/*
 * File main.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
if ($hidecomponent == 1) {
	?>
	<main id="sb-content" class="uk-width-<?php echo $content_width . $main_addclasses; ?>" role="main"<?php echo $main_addattr; ?>>
		<?php if ($main_container > 0) { ?>
			<div class="uk-container<?php echo ' uk-container-' . $main_container_width . $main_addclasses_container ?>"<?php echo $main_addattr_container ?>>
				<?php
			}
			?>
			<?php
			if ($content_width_array[1] > 6) {
				echo '<div class = "uk-width-1-1"><div class = "uk-alert uk-alert-warning"><i class = "uk-icon-exclamation-triangle uk-icon-small"></i> Sorry, content width class (uk-width-medium-' . $content_width . ') is not supported. Please change sidebar width to show correct grid.</div></div>';
			} else {
				if ($sb1_main_exist || $sb2_main_exist) {
					$grid_prefix = 'main';
					?>
																						<!-- main-grid-<?php echo $sb1_main_height . '-' . $sb2_main_height; ?> pattern included -->
					<?php
					include JPATH_THEMES . '/simple_blank/includes/patterns/grid-' . $sb1_main_height . '-' . $sb2_main_height . '.php';
					$grid_prefix = '';
					$html_prefix = ($grid_prefix != '') ? $grid_prefix . '-' : '';
					$var_prefix	 = ($grid_prefix != '') ? $grid_prefix . '_' : '';
				} else {
					include JPATH_THEMES . '/simple_blank/includes/sections/main-top.php';
					include JPATH_THEMES . '/simple_blank/includes/sections/main-main.php';
					include JPATH_THEMES . '/simple_blank/includes/sections/main-bottom.php';
				}
			}
			?>
			<?php if ($main_container > 0) { ?>
			</div>
			<?php
		}
		?>
	</main>
	<?php
}
