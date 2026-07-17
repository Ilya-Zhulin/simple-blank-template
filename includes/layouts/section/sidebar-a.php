<?php
/*
 * @package    DEV
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 05.05.2026, 12:49
 */

/*
 * File sidebar-a.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
// ВОССТАНАВЛИВАЕМ ВСЕ ПЕРЕМЕННЫЕ ИЗ КОНТРОЛЛЕРА
$displayData = $displayData ?? [];
$config      = $displayData['config'] ?? null;
if ($config)
{
	extract($config->data);
}
foreach ($sections['sb-sidebar-a'] as $sb_sidebar_a_position)
{
	if (is_array($sb_sidebar_a_position) && strtolower($sb_sidebar_a_position['pos-name']) !== 'sb-sidebar-a' && $config->getDoc()->countModules($sb_sidebar_a_position['pos-name']))
	{
		echo $config->_buildPosition('sb-sidebar-a', $sections);
	}
	else
	{
		echo '<jdoc:include type="modules" name="' . $sb_sidebar_a_position['pos-name'] . '" style="html5" />';
	}
}
