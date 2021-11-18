<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2021 Ilya A.Zhulin for SB Template
 * @license     GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

$app = JFactory::getApplication();

require_once JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php';

$twofactormethods = UsersHelper::getTwoFactorMethods();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<jdoc:include type="head" />
		<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/simple_blank/css/template.css" type="text/css" />
	</head>
	<body class="uk-height-viewport uk-flex uk-flex-middle uk-flex-center">
		<jdoc:include type="message" />
		<div id="frame" class="outline">
			<?php if ($app->get('offline_image') && file_exists($app->get('offline_image'))) : ?>
				<img src="<?php echo $app->get('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->get('sitename')); ?>" />
			<?php endif; ?>
			<h1 class="uk-heading uk-text-center">
				<?php echo htmlspecialchars($app->get('sitename')); ?>
			</h1>
			<?php if ($app->get('display_offline_message', 1) == 1 && str_replace(' ', '', $app->get('offline_message')) != '') : ?>
				<div class="uk-alert">
					<?php echo $app->get('offline_message'); ?>
				</div>
			<?php elseif ($app->get('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
				<div class="uk-alert">
					<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
				</div>
			<?php endif; ?>
			<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" class="uk-form uk-form-horizontal">
				<fieldset class="uk-fieldset">
					<div class="uk-margin" id="form-login-username">
						<label for="username" class="uk-form-label"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
						<div class="uk-form-controls">
							<input name="username" id="username" type="text" class="uk-input" alt="<?php echo JText::_('JGLOBAL_USERNAME'); ?>" size="18" />
						</div>
					</div>
					<div class="uk-margin" id="form-login-password">
						<label for="passwd" class="uk-form-label"><?php echo JText::_('JGLOBAL_PASSWORD'); ?></label>
						<div class="uk-form-controls">
							<input type="password" name="password" class="uk-input" size="18" alt="<?php echo JText::_('JGLOBAL_PASSWORD'); ?>" id="passwd" />
						</div>
					</div>
					<?php if (count($twofactormethods) > 1) : ?>
						<div class="uk-margin" id="form-login-secretkey">
							<label for="secretkey" class="uk-form-label"><?php echo JText::_('JGLOBAL_SECRETKEY'); ?></label>
							<div class="uk-form-controls">
								<input type="text" name="secretkey" class="uk-input" size="18" alt="<?php echo JText::_('JGLOBAL_SECRETKEY'); ?>" id="secretkey" />
							</div>
						</div>
					<?php endif; ?>
					<input type="submit" name="Submit" class="uk-button login" value="<?php echo JText::_('JLOGIN'); ?>" />
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.login" />
					<input type="hidden" name="return" value="<?php echo base64_encode(JUri::base()); ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</fieldset>
			</form>
			<p class="uk-text-center"><img src="templates/simple_blank/images/template_thumbnail.svg" alt="With Simple Blank Template" style="width:150px;"/></p>
		</div>
	</body>
</html>
