<?php
/*
 * @package    DEV
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 05.05.2026, 12:48
 */

defined('_JEXEC') or die('Restricted access');

$displayData = $displayData ?? [];
$config      = $displayData['config'] ?? null;
if ($config)
{
	extract($config->data);
}
foreach ($sb_bottom_sections_array as $sb_bottom_sections_item)
{
	if ($config->getDoc()->countModules($sb_bottom_sections_item) || (isset($sections[$sb_bottom_sections_item]) && $sections[$sb_bottom_sections_item]['isExist'] > 0))
	{
		echo $config->_buildPosition($sb_bottom_sections_item, $sections);
	}
}
