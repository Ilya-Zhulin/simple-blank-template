<?php defined('_JEXEC') or die; ?>

<ul class="uk-breadcrumb <?php echo $moduleclass_sfx; ?>">
<?php
	// if ($params->get('showHere', 1)) {
	// 	echo '<li class="active">' . JText::_('MOD_BREADCRUMBS_HERE') . '</li>';
	// } else {
	// 	echo '<li class="active"><span data-uk-tooltip title="' . JText::_('MOD_BREADCRUMBS_HERE') . '"><i class="uk-icon-map-marker"></i></span></li>';
	// }

	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++) {
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link) {
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);

	// Generate the trail
	foreach ($list as $key => $item) :
		if ($key != $last_item_key) {
			// Render all but last item - along with separator
			if (!empty($item->link)) {
				echo '<li><a href="' . $item->link . '">' . $item->name . '</a></li>';
			} else {
				echo '<li><span>' . $item->name . '</span></li>';
			}
		} elseif ($show_last) {
			// Render last item if reqd.
			echo '<li class="uk-active"><span>' . $item->name . '</span></li>';
		}
	endforeach;
?>
</ul>
