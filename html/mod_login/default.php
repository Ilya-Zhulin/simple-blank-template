<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::register('UsersHelperRoute', JPATH_SITE . '/components/com_users/helpers/route.php');

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure', 0)); ?>" method="post" id="login-form" class="uk-form-inline" >
    <?php if ($params->get('pretext')) : ?>
        <div class="uk-legend">
            <p><?php echo $params->get('pretext'); ?></p>
        </div>
    <?php endif; ?>
    <fieldset class="uk-fieldset">
        <div id="form-login-username" class="control-group">
            <div class="uk-margin">
                <?php if (!$params->get('usetext', 0)) : ?>
                    <label  for="modlgn-username" class="uk-hidden"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?></label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                        <input  id="modlgn-username" class="uk-input" type="text" tabindex="0" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?>" data-uk-tooltip title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?>">
                    </div>
                <?php else : ?>
                    <div class="uk-form-controls">
                        <input id="modlgn-username" type="text" name="username" class="uk-input validate[required,custom[email]]" tabindex="0" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME'); ?>" />
                        <label class="uk-form-label" for="modlgn-username"><span><?php echo JText::_('JGLOBAL_EMAIL'); ?></span></label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div id="form-login-password" class="control-group">
            <div class="uk-margin">
                <?php if (!$params->get('usetext', 0)) : ?>
                    <label for="modlgn-passwd" class="uk-hidden"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: lock"></span>
                        <input id="modlgn-passwd" type="password" name="password" class="uk-input" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" />
                    </div>
                <?php else : ?>
                    <div class="uk-form-controls">
                        <input id="modlgn-passwd" type="password" name="password" class="uk-input" tabindex="0" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" />
                        <label class="uk-form-label" for="modlgn-passwd"><span><?php echo JText::_('JGLOBAL_PASSWORD'); ?></span></label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (count($twofactormethods) > 1) : ?>
            <div id="form-login-secretkey" class="control-group">
                <div class="controls">
                    <?php if (!$params->get('usetext', 0)) : ?>
                        <div class="input-prepend input-append">
                            <span class="add-on">
                                <span class="icon-star hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>">
                                </span>
                                <label for="modlgn-secretkey" class="element-invisible"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?>
                                </label>
                            </span>
                            <input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" />
                            <span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
                                <span class="icon-help"></span>
                            </span>
                        </div>
                    <?php else : ?>
                        <label for="modlgn-secretkey"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
                        <input id="modlgn-secretkey" autocomplete="off" type="text" name="secretkey" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" />
                        <span class="btn width-auto hasTooltip" title="<?php echo JText::_('JGLOBAL_SECRETKEY_HELP'); ?>">
                            <span class="icon-help"></span>
                        </span>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>
        <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
            <div id="form-login-remember" class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                <label for="modlgn-remember"><input id="modlgn-remember" class="uk-checkbox uk-margin-small-right" value="yes" type="checkbox" checked><?php echo JText::_('MOD_LOGIN_REMEMBER_ME'); ?></label>
            </div>
        <?php endif; ?>
        <div id="form-login-submit" class="control-group">
            <div class="controls">
                <button type="submit" tabindex="0" name="Submit" class="uk-button uk-button-secondary login-button"><?php echo JText::_('JLOGIN'); ?></button>
            </div>
        </div>
        <?php $usersConfig = JComponentHelper::getParams('com_users'); ?>
        <ul class="uk-subnav">
            <?php /* if ($usersConfig->get('allowUserRegistration')) : ?>
              <li>
              <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
              <?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
              </li>
              <?php endif; */ ?>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                    <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
            </li>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                    <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
            </li>
        </ul>
        <input type="hidden" name="option" value="com_users" />
        <input type="hidden" name="task" value="user.login" />
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </fieldset>
    <?php if ($params->get('posttext')) : ?>
        <div class="uk-legend">
            <p><?php echo $params->get('posttext'); ?></p>
        </div>
    <?php endif; ?>
</form>
