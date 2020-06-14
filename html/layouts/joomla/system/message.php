<?php
// no direct access
defined('_JEXEC') or die;

$msgList = $displayData['msgList'];
?>

<div id="system-message-container">
    <?php if (is_array($msgList) && !empty($msgList)) : ?>
        <div id="system-message">
            <?php foreach ($msgList as $type => $msgs) :
                $type = ($type == 'error') ? 'warning' : $type;
                ?>
                <div class="uk-alert uk-alert-large uk-alert-<?php echo $type; ?>" data-uk-alert>
                    <a class="uk-alert-close uk-close"></a>
                    <?php if (!empty($msgs)) : ?>
                        <h2 class="uk-h2"><?php echo JText::_($type); ?></h2>
                        <?php foreach ($msgs as $msg) : ?>
                            <p><?php echo $msg; ?></p>
                        <?php endforeach; ?>
                <?php endif; ?>
                </div>
        <?php endforeach; ?>
        </div>
<?php endif; ?>
</div>
