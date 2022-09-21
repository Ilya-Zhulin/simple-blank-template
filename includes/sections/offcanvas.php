<?php
/*
 * File offcanvas-a.php is programmed
 * specially for Shop by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
foreach ($sb_offcanvas_array as $i => $sb_offcanvas_item) {
	if (${"offcanvas" . ($i + 1) . "_show"} == 1 && ($this->countModules($sb_offcanvas_item) || (isset($sections[$sb_offcanvas_item]) && $sections[$sb_offcanvas_item]['isExist'] > 0))) {
		?>
		<div id="<?php echo $sb_offcanvas_item ?>-wrapper" uk-offcanvas="mode: <?php echo ${"offcanvas" . ($i + 1) . "_animation"}; ?>; overlay: true; flip: <?php echo ${"offcanvas" . ($i + 1) . "_flip"}; ?>">
			<div class="uk-offcanvas-bar">
				<?php
				if (isset($offcanvas1_close) && $offcanvas1_close > 0) {
					?>
					<button class="sb-offcanvas1-close uk-offcanvas-close
							<?php
							if ($offcanvas1_close_large > 0) {
								echo " uk-close-large";
							}
							?>
							" type = "button" uk-close></button>
						<?php }
						?>
						<?php
						echo _buildPosition($this, $sb_offcanvas_item, $tplparams, $sections);
						?>
			</div>
		</div>
		<?php
	}
}