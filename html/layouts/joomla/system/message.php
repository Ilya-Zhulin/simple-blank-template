<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.protostar
 *
 * @copyright   Copyright (C) 2021 Ilya A.Zhulin. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

// J6: остаётся UIkit-вариант сообщений (осознанный отказ от webcomponent.joomla-alert),
// маппинг типов — по константам CMSApplication (J6), контейнер с aria-live

$msgList = $displayData['msgList'];

$alert      = array(
	CMSApplication::MSG_EMERGENCY => 'danger',
	CMSApplication::MSG_ALERT     => 'danger',
	CMSApplication::MSG_CRITICAL  => 'danger',
	CMSApplication::MSG_ERROR     => 'danger',
	CMSApplication::MSG_WARNING   => 'warning',
	CMSApplication::MSG_NOTICE    => 'info',
	CMSApplication::MSG_INFO      => 'info',
	CMSApplication::MSG_DEBUG     => 'info',
	CMSApplication::MSG_MESSAGE   => 'success',
);
$icon       = array('error' => 'close', 'warning' => 'warning', 'notice' => 'info', 'message' => 'check');
$app        = Factory::getApplication();
$template   = $app->getTemplate(true);
$alert_view = $template->params->get('alert_layout', 'defaultValue');
$theme      = $template->params->get('theme_select', 'default_theme');
$this_path  = str_replace(JPATH_THEMES . '/' . $template->template, '', __FILE__);
if ($theme !== 'default_theme' && file_exists(JPATH_THEMES . '/' . $template->template . '/themes/' . $theme . $this_path))
{
    include_once JPATH_THEMES . '/' . $template->template . '/themes/' . $theme . $this_path;
}
else
{
    ?>
    <div id="system-message-container" aria-live="polite">
        <?php if (is_array($msgList) && !empty($msgList)) { ?>
            <div id="system-message">
                <?php
                foreach ($msgList as $type => $msgs)
                {
                    if (!empty($msgs))
                    {
                        switch ($alert_view)
                        {
                        case 0: // alert
                        foreach ($msgs as $msg)
                        {
                            ?>
                            <div class="uk-alert uk-alert-<?php echo $alert[$type]; ?>" uk-alert>
                                <a class="uk-alert-close" uk-close></a>
                                <p><?php echo $msg; ?></p>
                            </div>
                        <?php
                        }
                        break;
                        case 1:
                        foreach ($msgs

                        as $msg)
                        {
                        ?>
                            <script>
                                UIkit.notification({
                                    message: '<div uk-grid><div class="uk-width-expand"><h4 class="uk-light uk-text-center "><?php echo Text::_($type); ?></h4><div uk-grid class="uk-grid-collapse"><div class="uk-width-auto"><span uk-icon="icon: <?php echo $icon[$type]; ?>; ratio: 3" class="uk-icon-left"></span></div><div class="uk-width-expand"><p><?php echo $msg; ?></p></div></div></div><div class="uk-width-auto"><img src="/media/templates/site/<?php echo $template->template; ?>/images/favicon/favicon.svg" style="width:100px;" class="uk-align-right" /></div></div>',
                                    status: '<?php echo $alert[$type]; ?>',
                                    pos: 'bottom-center',
                                    timeout: 15000
                                });
                            </script>
                            <?php
                        }
                            break;
                        }
                    }
                }
                ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
