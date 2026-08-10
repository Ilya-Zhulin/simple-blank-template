<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// J6 REVIEW: переопределение стандартного layout'а с UIkit-разметкой
// Проверить использование и адаптировать под Joomla 6 API

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

extract($displayData);

/**
 * Layout variables
 * ---------------------
 *    $options         : (array)  Optional parameters
 *    $label           : (string) The html code for the label (not required if $options['hiddenLabel'] is true)
 *    $input           : (string) The input field html code
 *    $id              : (string) The id of the input this label is for
 *    $name            : (string) The name of the input this label is for
 *    $description     : (string) An optional description to use as in–line help text
 */

if (!empty($options['showonEnabled']))
{
	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
	$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	$wa->useScript('showon');
}

$class	 = empty($options['class']) ? '' : ' ' . $options['class'];
$rel	 = empty($options['rel']) ? '' : ' ' . $options['rel'];
$id	 = ($id ?? $name) . '-desc';
$hideLabel	 = !empty($options['hiddenLabel']);
$hideDescription = empty($options['hiddenDescription']) ? false : $options['hiddenDescription'];
$descClass	 = ($options['descClass'] ?? '') ?: (!empty($options['inlineHelp']) ? 'hide-aware-inline-help d-none' : '');

/**
 * @TODO:
 *
 * As mentioned in #8473 (https://github.com/joomla/joomla-cms/pull/8473), ...
 * as long as we cannot access the field properties properly, this seems to
 * be the way to go for now.
 *
 * On a side note: Parsing html is seldom a good idea.
 * https://stackoverflow.com/questions/1732348/regex-match-open-tags-except-xhtml-self-contained-tags/1732454#1732454
 */
preg_match('/class=\"([^\"]+)\"/i', $input, $match);

$required	 = (strpos($input, 'aria-required="true"') !== false || (!empty($match[1]) && strpos($match[1], 'required') !== false));
$typeOfSpacer	 = (strpos($label, 'spacer-lbl') !== false);
?>

<div class="uk-margin control-group<?php echo $class; ?>"<?php echo $rel; ?>>
	<?php if ($hideLabel): ?>
		<div class="visually-hidden"><?php echo $label; ?></div>
	<?php else: ?>
		<div class="uk-form-label"><?php echo $label; ?>
			<?php if (!$required && !$typeOfSpacer) : ?>
				<span class="optional"><?php echo Text::_('COM_USERS_OPTIONAL'); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="uk-form-controls">
		<?php echo $input; ?>
		<?php if (!$hideDescription && !empty($description)) : ?>
			<div id="<?php echo $id; ?>" class="<?php echo $descClass; ?>">
				<small class="uk-text-meta">
					<?php echo $description; ?>
				</small>
			</div>
		<?php endif; ?>
	</div>
</div>
