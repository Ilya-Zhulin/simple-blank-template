<?php

/**
 * @package     Joomla.Site
 * @subpackage  Template.protostar
 *
 * @copyright   Copyright (C) 2021 Ilya A.Zhulin. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\Factory;

\defined('_JEXEC') or die;

$msgList = $displayData['msgList'];

$alert      = array('error' => 'danger', 'warning' => 'warning', 'notice' => '', 'message' => 'success');
$icon       = array('error' => 'close', 'warning' => 'warning', 'notice' => 'info', 'message' => 'check');
$app        = Factory::getApplication();
$template   = $app->getTemplate(true);
$alert_view = $template->params->get('alert_layout', '0');
$theme      = $template->params->get('theme_select', 'default_theme');
$this_path  = str_replace(JPATH_THEMES . '/simple_blank', '', __FILE__);
if ($theme !== 'default_theme' && file_exists(JPATH_THEMES . '/simple_blank/themes/' . $theme . $this_path)) {
    include_once JPATH_THEMES . '/simple_blank/themes/' . $theme . $this_path;
} else {
    ?>
    <div id="system-message-container">
        <?php if (is_array($msgList) && !empty($msgList)) { ?>
            <div id="system-message">
                <?php
                foreach ($msgList as $type => $msgs) {
                    if (!empty($msgs)) {
                        switch ($alert_view) {
                            case 0: // alert
                                foreach ($msgs as $msg) {
                                    ?>
                                    <div class="uk-alert uk-alert-<?php echo $alert[$type]; ?>" uk-alert>
                                        <a class="uk-alert-close" uk-close></a>
                                        <p><?php echo $msg; ?></p>
                                    </div>
                                    <?php
                                }
                                break;
                            case 1:
                                foreach ($msgs as $msg) {
                                    ?>
                                    <script>
                                        UIkit.notification({
                                            message: '<div uk-grid><div class="uk-width-expand"><h4 class="uk-light uk-text-center "><?php echo JText::_($type); ?></h4><div uk-grid class="uk-grid-collapse"><div class="uk-width-auto"><span uk-icon="icon: <?php echo $icon[$type]; ?>; ratio: 3" class="uk-icon-left"></span></div><div class="uk-width-expand"><p><?php echo $msg; ?></p></div></div></div><div class="uk-width-auto"></div></div>',
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