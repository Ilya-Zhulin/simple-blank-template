<?php
/*
 * @package    DEV
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 05.05.2026, 20:31
 */

/*
 * File offcanvas-a.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2022
 */

defined('_JEXEC') or die('Restricted access');
$displayData = $displayData ?? [];
$config      = $displayData['config'] ?? null;
if ($config)
{
    extract($config->data);
}
$sb_offcanvas_array = ['sb-off-canvas-a', 'sb-off-canvas-b'];
foreach ($sb_offcanvas_array as $i => $sb_offcanvas_item)
{
    if (${"offcanvas" . ($i + 1) . "_show"} == 1 && ($config->getDoc()->countModules($sb_offcanvas_item) || (isset($sections[$sb_offcanvas_item]) && $sections[$sb_offcanvas_item]['isExist'] > 0)))
    {
        ?>
        <?php echo \SimpleBlank\Site\Helper\DevHelper::section($sb_offcanvas_item, __FILE__); ?>
        <<?php echo ${"offcanvas" . ($i + 1) . "_tag"}; ?> id="<?php echo $sb_offcanvas_item ?>-wrapper" uk-offcanvas="mode: <?php echo ${"offcanvas" . ($i + 1) . "_animation"}; ?>; overlay: true; flip: <?php echo ${"offcanvas" . ($i + 1) . "_flip"}; ?>" class="<?php echo ${"offcanvas" . ($i + 1) . "_addclasses"} ?>"<?php echo ${"offcanvas" . ($i + 1) . "_addattr"} ?>>
        <div class="uk-offcanvas-bar <?php echo ${"offcanvas" . ($i + 1) . "_bar_addclasses"} ?>"<?php echo ${"offcanvas" . ($i + 1) . "_bar_addattr"} ?>>
            <?php
            if (isset(${"offcanvas" . ($i + 1) . "_close"}) && ${"offcanvas" . ($i + 1) . "_close"} > 0)
            {
                ?>
                <button class="sb-offcanvas1-close uk-offcanvas-close
						<?php
                if (${"offcanvas" . ($i + 1) . "_close_large"} > 0)
                {
                    echo " uk-close-large";
                }
                ?>
						" type="button" uk-close></button>
                <?php
            }
            ?>
            <?php
            if ($sections[$sb_offcanvas_item]['isExist'] > 0)
            {
                foreach ($sections[$sb_offcanvas_item] as $offcanvas_position)
                {
                    if (is_array($offcanvas_position))
                    {
                        ?>
                        <?php echo \SimpleBlank\Site\Helper\DevHelper::modules($offcanvas_position['pos-name'], __FILE__); ?>
                        <?php
                    }
                }
            }
            ?>
        </div>
        </<?php echo ${"offcanvas" . ($i + 1) . "_tag"}; ?>>
        <?php
    }
}
