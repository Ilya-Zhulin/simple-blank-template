<?php
/*
 * File offcanvas-a.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
foreach ($sb_offcanvas_array as $i => $sb_offcanvas_item) {
	if (${"offcanvas" . ($i + 1) . "_show"} == 1 && ($this->countModules($sb_offcanvas_item) || (isset($sections[$sb_offcanvas_item]) && $sections[$sb_offcanvas_item]['isExist'] > 0))) {
		?>
		<<?php echo ${"offcanvas" . ($i + 1) . "_tag"}; ?> id="<?php echo $sb_offcanvas_item ?>-wrapper" uk-offcanvas="mode: <?php echo ${"offcanvas" . ($i + 1) . "_animation"}; ?>; overlay: true; flip: <?php echo ${"offcanvas" . ($i + 1) . "_flip"}; ?>" class="<?php echo ${"offcanvas" . ($i + 1) . "_addclasses"} ?>"<?php echo ${"offcanvas" . ($i + 1) . "_addattr"} ?>>
		<div class="uk-offcanvas-bar <?php echo ${"offcanvas" . ($i + 1) . "_bar_addclasses"} ?>"<?php echo ${"offcanvas" . ($i + 1) . "_bar_addattr"} ?>>
			<?php
			if (isset(${"offcanvas" . ($i + 1) . "_close"}) && ${"offcanvas" . ($i + 1) . "_close"} > 0) {
				?>
				<button class="sb-offcanvas1-close uk-offcanvas-close
						<?php
						if (${"offcanvas" . ($i + 1) . "_close_large"} > 0) {
							echo " uk-close-large";
						}
						?>
						" type = "button" uk-close></button>
					<?php }
					?>
					<?php
					if ($sections[$sb_offcanvas_item]['isExist'] > 0) {
						foreach ($sections[$sb_offcanvas_item] as $offcanvas_position) {
							if (is_array($offcanvas_position)) {
								?>
						<jdoc:include type="modules" name="<?php echo $offcanvas_position['pos-name']; ?>" />
						<?php
					}
				}
			}
			?>
		</div>
		</<?php echo ${"offcanvas" . ($i + 1) . "_tag"}; ?>>
		<?php
	}
}