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
$bottomPositions = ['sb-bottom-a', 'sb-bottom-b', 'sb-bottom-c'];
foreach ($bottomPositions as $posName)
{
	if ($config->getDoc()->countModules($posName) || (isset($sections[$posName]) && $sections[$posName]['isExist'] > 0))
	{
		echo $config->_buildPosition($posName, $sections);
	}
}
