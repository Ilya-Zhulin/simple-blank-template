<?php
/**
 * @package         Joomla.Site
 * @subpackage      mod_menu
 * @version         Simple Blank Custom (UIKit Adapted)
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Filter\OutputFilter;

$attributes = [];

if ($item->anchor_title)
{
	$attributes['title'] = $item->anchor_title;
}

if ($item->anchor_css)
{
	$attributes['class'] = $item->anchor_css;
}

if ($item->anchor_rel)
{
	$attributes['rel'] = $item->anchor_rel;
}

// Accessibility: Aria current
if ($item->id == $active_id)
{
	$attributes['aria-current'] = 'location';
	if ($item->current)
	{
		$attributes['aria-current'] = 'page';
	}
}

$linktype = $item->title;

// --- Обработка иконок (Адаптация под UIKit) ---
if ($item->menu_icon)
{
	// Ссылка содержит иконку
	if ($itemParams->get('menu_text', 1))
	{
		// Текст отображается + иконка
		// Убраны классы Bootstrap p-2 pt-0.
		// Добавлен uk-margin-small-right для отступа от текста.
		// Класс иконки остается тем, что задан в меню (fa-home, uk-icon-home и т.д.)
		$linktype = '<span class="' . $item->menu_icon . ' uk-margin-small-right" aria-hidden="true"></span>' . $item->title;
	}
	else
	{
		// Только иконка (текст скрыт)
		$linktype = '<span class="' . $item->menu_icon . '" aria-hidden="true"></span><span class="uk-hidden-visually">' . $item->title . '</span>';
	}
}
elseif ($item->menu_image)
{
	// Ссылка содержит изображение
	$image_attributes = [];

	if ($item->menu_image_css)
	{
		$image_attributes['class'] = $item->menu_image_css;
	}

	// Если нет своих классов, добавим базовые для аккуратности (опционально)
	// if (empty($item->menu_image_css)) { $image_attributes['class'] = 'uk-preserve-width'; }

	$linktype = HTMLHelper::_('image', $item->menu_image, '', $image_attributes);

	// Текст рядом с картинкой (скрытый или видимый)
	$textClass = $itemParams->get('menu_text', 1) ? '' : ' uk-hidden-visually';
	$linktype  .= '<span class="image-title' . $textClass . '">' . $item->title . '</span>';
}

// --- Обработка поведения окна (target/_blank и т.д.) ---
if ($item->browserNav == 1)
{
	$attributes['target'] = '_blank';
	// Для безопасности внешних ссылок часто добавляют rel="noopener noreferrer"
	if (empty($attributes['rel']))
	{
		$attributes['rel'] = 'noopener noreferrer';
	}
	elseif (!str_contains($attributes['rel'], 'noopener'))
	{
		$attributes['rel'] .= ' noopener noreferrer';
	}
}
elseif ($item->browserNav == 2)
{
	$options               = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';
	$attributes['onclick'] = "window.open(this.href, 'targetWindow', '" . $options . "'); return false;";
}

// Вывод ссылки
echo HTMLHelper::_(
	'link',
	OutputFilter::ampReplace(htmlspecialchars($item->flink, ENT_COMPAT, 'UTF-8', false)),
	$linktype,
	$attributes
);
