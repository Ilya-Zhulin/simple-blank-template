<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>

<?php if ($params->get('backgroundimage')): ?>
	<div style="background-image:url(<?php echo $params->get('backgroundimage'); ?>)">
	<?php endif; ?>
	<?php echo $module->content; ?>
	<?php if ($params->get('backgroundimage')): ?>
	</div>
<?php endif; ?>
