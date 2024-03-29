<?php
/*
 * File grid-1-1.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
$html_prefix	 = ($grid_prefix != '') ? $grid_prefix . '-' : '';
$var_prefix		 = ($grid_prefix != '') ? $grid_prefix . '_' : '';
$content_width	 = $fraction(60 - ${'sb1_' . $var_prefix . 'real_width'});
?>
<div uk-grid class="<?php echo $patternclass; ?>">
	<?php if (${'sb2_' . $var_prefix . 'exist'} && ${'sb2_' . $var_prefix . 'position'} == 1) { ?>
		<div class="<?php echo $html_prefix; ?>sidebar-b-wrapper left-<?php echo $html_prefix; ?>sidebar-wrapper uk-width-<?php echo $fraction(${'sb2_' . $var_prefix . 'real_width'}); ?>@s">
			<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'sidebar-b.php'; ?>
		</div>
	<?php } ?>
	<div class="uk-width-expand@s">
		<div uk-grid class="uk-child-width-1-1">
			<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'top.php'; ?>
			<?php if (${'sb1_' . $var_prefix . 'exist'} && ${'sb1_' . $var_prefix . 'position'} == 1) { ?>
				<div class="<?php echo $html_prefix; ?>sidebar-a-wrapper left-<?php echo $html_prefix; ?>sidebar-wrapper uk-width-<?php echo $fraction(${'sb1_' . $var_prefix . 'real_width'}); ?>@s">
					<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'sidebar-a.php'; ?>
				</div>
			<?php } ?>
			<div class="uk-width-expand">
				<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'main.php'; ?>
				<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'bottom.php'; ?>
			</div>
			<?php if (${'sb1_' . $var_prefix . 'exist'} && ${'sb1_' . $var_prefix . 'position'} == 0) { ?>
				<div class="<?php echo $html_prefix; ?>sidebar-a-wrapper right-<?php echo $html_prefix; ?>sidebar-wrapper uk-width-<?php echo $fraction(${'sb1_' . $var_prefix . 'real_width'}); ?>@s">
					<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'sidebar-a.php'; ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php if (${'sb2_' . $var_prefix . 'exist'} && ${'sb2_' . $var_prefix . 'position'} == 0) { ?>
		<div class="<?php echo $html_prefix; ?>sidebar-b-wrapper right-<?php echo $html_prefix; ?>sidebar-wrapper uk-width-<?php echo $fraction(${'sb2_' . $var_prefix . 'real_width'}); ?>@s">
			<?php include_once JPATH_THEMES . '/simple_blank/includes/sections/' . $html_prefix . 'sidebar-b.php'; ?>
		</div>
	<?php } ?>
</div>