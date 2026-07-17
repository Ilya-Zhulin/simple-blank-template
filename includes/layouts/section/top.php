<?php
/*
 * @package    DEV
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 05.05.2026, 16:44
 */

/*
 * File top.php
 * Updated for Simple Blank Template with ThemeManager architecture
 */

defined('_JEXEC') or die;

$displayData = $displayData ?? [];
$config      = $displayData['config'] ?? null;
if ($config)
{
	extract($config->data);
}
$topPositions = ['sb-top-a', 'sb-top-b', 'sb-top-c'];
foreach ($topPositions as $posName)
{
	// Проверяем: есть ли эта секция в массиве И активна ли она (isExist > 0)
	if (isset($sections[$posName]) && $sections[$posName]['isExist'] > 0)
	{
		echo $config->_buildPosition($posName, $sections);
	}
}
