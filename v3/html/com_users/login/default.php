<?php
/*
 * File login.php is programmed
 * specially for mk-graphics by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2021
 */

defined('_JEXEC') or die('Restricted access');
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');
?>
<div class="login<?php echo $this->pageclass_sfx; ?> uk-flex uk-flex-middle uk-flex-center" uk-height-viewport>
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description','')) != '') || $this->params->get('login_image') != '') : ?>
		<div class="login-description">
		<?php endif; ?>
		<?php if ($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>
		<?php if ($this->params->get('login_image') != '') : ?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JText::_('COM_USERS_LOGIN_IMAGE_ALT'); ?>" />
		<?php endif; ?>
		<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description','')) != '') || $this->params->get('login_image') != '') : ?>
		</div>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="uk-form form-validate uk-form-horizontal well">
		<fieldset class="uk-fieldset">
			<?php echo $this->form->renderFieldset('credentials'); ?>
			<?php if ($this->tfa) : ?>
				<?php echo $this->form->renderField('secretkey'); ?>
			<?php endif; ?>
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<div class="uk-margin">
					<label for="remember">
						<?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME'); ?><input id="remember" type="checkbox" name="remember" class="uk-checkbox uk-margin-small-left" value="yes" />
					</label>
				</div>
			<?php endif; ?>
			<div class="uk-margin">
				<button type="submit" class="uk-button uk-button-primary">
					<?php echo JText::_('JLOGIN'); ?>
				</button>
			</div>
			<?php $return		 = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem'))); ?>
			<input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
			<?php echo JHtml::_('form.token'); ?>
			<div>
				<ul class="uk-subnav">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
							<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
							<?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?>
						</a>
					</li>
					<?php $usersConfig = JComponentHelper::getParams('com_users'); ?>
					<?php if ($usersConfig->get('allowUserRegistration')) : ?>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
								<?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</fieldset>
	</form>
</div>

