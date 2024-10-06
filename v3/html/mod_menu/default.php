<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @version 24.11.2023
 */
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Version;

if ((int) Version::MAJOR_VERSION >= 4) {
	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
	$wa = $app->getDocument()->getWebAssetManager();
	$wa->registerAndUseScript('mod_menu', 'mod_menu/menu.min.js', [], ['type' => 'module']);
	$wa->registerAndUseScript('mod_menu', 'mod_menu/menu-es5.min.js', [], ['nomodule' => true, 'defer' => true]);
}
$id = '';

if ($tagId = $params->get('tag_id', '')) {
	$id = ' id="' . $tagId . '"';
}

// The menu class is deprecated. Use nav instead
?>
<ul class="mod-menu mod-list nav <?php echo $class_sfx; ?> mod-list"<?php echo $id; ?> uk-nav>
	<?php
	foreach ($list as $i => &$item) {
		if ($item->id == $active_id || ($item->type === 'alias' && $item->params->get('aliasoptions') == $active_id)) {
			$current_item = $item;
		}
	}
	foreach ($list as $i => &$item) {

		$class = 'item-' . $item->id;

		if ($item->id == $default_id) {
			$class .= ' default';
		}

		if ($item->id == $active_id || ($item->type === 'alias' && $item->params->get('aliasoptions') == $active_id)) {
			$class .= ' current uk-active';
		}

		if (in_array($item->id, $path)) {
			$class .= ' uk-active';
		} elseif ($item->type === 'alias') {
			$aliasToId = $item->params->get('aliasoptions');

			if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
				$class .= ' uk-active';
			} elseif (in_array($aliasToId, $path)) {
				$class .= ' alias-parent-active';
			}
		}

		if ($item->type === 'separator') {
			$class .= ' divider';
		}

		if ($item->deeper) {
			$class .= ' deeper';
		}

		if ($item->parent && ($params->get('endLevel') == '0' || ((int) $params->get('endLevel') > (int) $params->get('startLevel')))) {
			$class .= ' uk-parent';
			if (isset($current_item->parent_id) && $current_item->parent_id == $item->id) {
				$class .= ' uk-open';
			}
		}



		echo '<li class="' . $class . '">';

		switch ($item->type) :
			case 'separator':
			case 'component':
			case 'heading':
			case 'url':
				require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
				break;

			default:
				require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
				break;
		endswitch;

		// The next item is deeper.
		if ($item->deeper) {
			echo '<ul class="nav-child unstyled small uk-nav-sub">';
		}
		// The next item is shallower.
		elseif ($item->shallower) {
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else {
			echo '</li>';
		}
	}
	?>
</ul>
