<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

function modChrome_panel($module, &$params, &$attribs) {
	$db				 = JFactory::getDbo();
	$db->setQuery('select note from #__modules where id=' . $module->id);
	$note			 = $db->loadResult();
	$moduleTag		 = $params->get('module_tag', 'div');
	$bootstrapSize	 = (int) $params->get('bootstrap_size', 0);
	$moduleClass	 = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';
	$headerTag		 = htmlspecialchars($params->get('header_tag', 'h3'));
	$headerClass	 = htmlspecialchars($params->get('header_class', 'uk-panel-title'));
	if (preg_match("!\*subtitle\*(.*?)\*/subtitle\*!si", $note, $matches) > 0) {
		$subtitle	 = '<div>' . $matches[1] . '</div>';
		$headerClass = ($headerClass !== '') ? $headerClass . ' uk-navbar-nav-subtitle"' : 'class="uk-navbar-nav-subtitle"';
	} else {
		$headerClass .= '"';
	}
	if ($module->content) {
		echo '<' . $moduleTag . ' class="uk-panel ' . htmlspecialchars($params->get('moduleclass_sfx')) . $moduleClass . '">';

		if ($module->showtitle) {
			echo '<' . $headerTag . ' class="' . $headerClass . '">' . $module->title . '</' . $headerTag . '>' . $subtitle;
		}

		echo $module->content;

		echo '</' . $moduleTag . '>';
	}
}

function modChrome_modal($module, &$params, &$attribs) {
	$db			 = JFactory::getDbo();
	$db->setQuery('select note from #__modules where id=' . $module->id);
	$note		 = $db->loadResult();
	$headerClass = $params->get('header_class');
	$headerClass = ($headerClass) ? ' class="' . htmlspecialchars($headerClass) : '';
	$moduleClass = htmlspecialchars($params->get('moduleclass_sfx'));
	$subtitle	 = '';
	if (preg_match("!\*subtitle\*(.*?)\*/subtitle\*!si", $note, $matches) > 0) {
		$subtitle	 = '<div>' . $matches[1] . '</div>';
		$headerClass = ($headerClass !== '') ? $headerClass . ' uk-navbar-nav-subtitle"' : 'class="uk-navbar-nav-subtitle"';
	} else {
		$headerClass .= ($headerClass !== '') ? '"' : '';
	}
	switch ($module->id) {
		case 229:
			$pre_icon	 = '<i class="uk-icon uk-icon-user"></i>&nbsp;';
			break;
		case 227:
			$pre_icon	 = '<i class="uk-icon uk-icon-user-plus"></i>&nbsp;';
			break;
		case 231:
			$pre_icon	 = '<i class="uk-icon uk-icon-question-circle"></i>&nbsp;';
			break;
		case 165:
			$pre_icon	 = '<i class="uk-icon uk-icon-edit"></i>&nbsp;';
			break;
		default:
			$pre_icon	 = '';
	}
	?>
	<a href="#module-<?php echo $module->id; ?>" <?php echo $headerClass; ?> uk-toggle><?php echo $pre_icon . $module->title . $subtitle; ?></a>
	<div id="module-<?php echo $module->id; ?>" uk-modal>
		<div class="uk-modal-dialog <?php echo $moduleClass; ?>">
			<a class="uk-modal-close-default" uk-close></a>
			<?php echo $module->content; ?>
		</div>
	</div>
	<?php
}

function modChrome_dropdown($module, &$params, &$attribs) {
	$db			 = JFactory::getDbo();
	$db->setQuery('select note from #__modules where id=' . $module->id);
	$note		 = $db->loadResult();
	$headerClass = $params->get('header_class');
	$headerClass = ($headerClass) ? ' class="' . htmlspecialchars($headerClass) . '"' : '';
	$moduleClass = htmlspecialchars($params->get('moduleclass_sfx'));
	if (preg_match("!\*subtitle\*(.*?)\*/subtitle\*!si", $note, $matches) > 0) {
		$subtitle	 = '<div>' . $matches[1] . '</div>';
		$headerClass = ($headerClass !== '') ? $headerClass . ' uk-navbar-nav-subtitle"' : 'class="uk-navbar-nav-subtitle"';
	} else {
		$headerClass .= ($headerClass !== '') ? '"' : '';
	}
	if ((bool) $module->showtitle) {
		?>
		<a id="dropdown-title-<?php echo $module->id; ?>" <?php echo $headerClass; ?> href=''>
			<?php echo $module->title . ((isset($subtitle) && strlen($subtitle) > 0) ? $subtitle : ''); ?>&nbsp;<i class="uk-icon-caret-down"></i>
		</a>
		<?php
	}
	?>
	<!-- This is the dropdown -->
	<div id="dropdown-content-<?php echo $module->id; ?>" class="uk-navbar-dropdown <?php echo $moduleClass; ?>" uk-dropdown="toggle: #dropdown-title-<?php echo $module->id; ?>;<?php echo htmlspecialchars($params->get('window_open')); ?>">
		<?php echo $module->content; ?>
	</div>

	<?php
}

/*
 * html5 (chosen html5 tag and font header tags)
 */

function modChrome_html5_clean($module, &$params, &$attribs) {
	$moduleTag		 = htmlspecialchars($params->get('module_tag', 'div'), ENT_QUOTES, 'UTF-8');
	$headerTag		 = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');
	$bootstrapSize	 = (int) $params->get('bootstrap_size', 0);
	$moduleClass	 = $bootstrapSize !== 0 ? ' span' . $bootstrapSize : '';

	// Temporarily store header class in variable
	$headerClass = $params->get('header_class');
	$headerClass = !empty($headerClass) ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty($module->content)) :
		if (strlen($params->get('moduleclass_sfx')) > 0) {
			?>
			<<?php echo $moduleTag; ?> class="<?php echo htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') . $moduleClass; ?>">
			<?php
		}
		if ((bool) $module->showtitle) :
			?>
			<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
			<?php
		endif;
		echo $module->content;
		if (strlen($params->get('moduleclass_sfx')) > 0) {
			?>
			</<?php echo $moduleTag; ?>>
			<?php
		}
	endif;
}
