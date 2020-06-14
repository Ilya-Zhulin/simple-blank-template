<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$class	 = '';
$attr	 = '';
if (($module->showtitle == '1') && ($module->position == 'offcanvas-a' || $module->position == 'offcanvas-b' || $module->position == 'sidebar-a' || $module->position == 'sidebar-b')) {
	$headerTag	 = htmlspecialchars($params->get('header_tag', ''));
	$headerClass = htmlspecialchars($params->get('header_class', ''));
	echo '<' . $headerTag . ' class="' . $headerClass . '">' . $module->title . '</' . $headerTag . '>';
}
?>

<ul class="<?php echo $class_sfx; ?>"<?php
$tag = '';

if ($params->get('tag_id') != null) {
	$tag = $params->get('tag_id') . '';
	echo ' id="' . $tag . '"';
}
?>>

	<?php
	foreach ($list as $i => &$item) {
		$class = 'item-' . $item->id;

		if (($item->id == $active_id) OR ( $item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id)) {
			$class .= ' current';
		}
		$liclass = '';
		if (in_array($item->id, $path)) {
			$liclass = ' class="uk-active"';
		} elseif ($item->type == 'alias') {
			$aliasToId = $item->params->get('aliasoptions');

			if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
				$class .= ' uk-active';
			} elseif (in_array($aliasToId, $path)) {
				$class .= ' alias-parent-active';
			}
		}

		if ($item->type == 'separator') {
			$class .= ' divider';
		}

		if ($item->deeper) {
			$class .= ' deeper';
		}

		if ($item->parent) {
			$class .= ' uk-parent';
		}

		if ($item->deeper) {
			$attr .= ' data-uk-dropdown';
		}

		if (!empty($class)) {
			$class = ' class="' . trim($class) . '"';
		}

		echo '<li' . $liclass . '>';

		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'component':
			case 'heading':
				require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
				break;
		endswitch;

		if ($item->deeper) { // The next item is deeper.
			echo '<div class="uk-navbar-dropdown"><ul class="uk-nav uk-navbar-dropdown-nav">';
		} elseif ($item->shallower) { // The next item is shallower.
			echo '</li>';
			echo str_repeat('</ul></div></li>', $item->level_diff);
		} else { // The next item is on the same level.
			echo '</li>';
		}
	}
	?>
</ul>
