<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Users\Site\View\Login\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')
        ->useScript('form.validate');

$usersConfig = ComponentHelper::getParams('com_users');
?>
<div uk-height-viewport="expand: true" class="uk-flex uk-flex-center uk-flex-middle">
    <div>
        <form action="<?php echo Route::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="uk-form uk-form-horizontal" id="com-users-login__form">

            <fieldset class="uk-fieldset">
                <?php echo $this->form->renderFieldset('credentials', ['class' => 'com-users-login__input']); ?>

                <?php if (PluginHelper::isEnabled('system', 'remember')) : ?>
                    <div class="uk-margin com-users-login__remember">
                        <div class="form-check uk-form-controls">
                            <label class="form-check-label" for="remember">
                                <input class="form-check-input uk-checkbox uk-margin-small-right" id="remember" type="checkbox" name="remember" value="yes">
                                <?php echo Text::_('COM_USERS_LOGIN_REMEMBER_ME'); ?>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                foreach ($this->extraButtons as $button) :
                    $dataAttributeKeys = array_filter(array_keys($button), function ($key) {
                        return substr($key, 0, 5) == 'data-';
                    });
                    ?>
                    <div class="com-users-login__submit control-group uk-margin">
                        <div class="controls uk-button-group">
                            <button type="button"
                                    class="uk-button uk-button-secondary"
                                    <?php foreach ($dataAttributeKeys as $key) : ?>
                                        <?php echo $key ?>="<?php echo $button[$key] ?>"
                                    <?php endforeach; ?>
                                    <?php if ($button['onclick']) : ?>
                                        onclick="<?php echo $button['onclick'] ?>"
                                    <?php endif; ?>
                                    title="<?php echo Text::_($button['label']) ?>"
                                    id="<?php echo $button['id'] ?>"
                                    >
                                        <?php if (!empty($button['icon'])) : ?>
                                    <span class="<?php echo $button['icon'] ?>"></span>
                                <?php elseif (!empty($button['image'])) : ?>
                                    <?php
                                    echo HTMLHelper::_('image', $button['image'], Text::_($button['tooltip'] ?? ''), [
                                        'class' => 'icon',
                                            ], true)
                                    ?>
                                <?php elseif (!empty($button['svg'])) : ?>
                                    <?php echo str_replace(['<svg', '</svg>'], ['<span class="uk-icon"><svg height="20" ', '</svg></span>'], $button['svg']); ?>
                                <?php endif; ?>
                                <?php echo Text::_($button['label']) ?>
                            </button>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary uk-button uk-button-primary">
                        <?php echo Text::_('JLOGIN'); ?>
                    </button>
                </div>

                <?php $return = $this->form->getValue('return', '', $this->params->get('login_redirect_url', $this->params->get('login_redirect_menuitem', ''))); ?>
                <input type="hidden" name="return" value="<?php echo base64_encode($return); ?>">
                <?php echo HTMLHelper::_('form.token'); ?>
            </fieldset>
        </form>
        <div class="uk-subnav">
            <a class="com-users-login__reset list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=reset'); ?>">
                <?php echo Text::_('COM_USERS_LOGIN_RESET'); ?>
            </a>
            <a class="com-users-login__remind list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=remind'); ?>">
                <?php echo Text::_('COM_USERS_LOGIN_REMIND'); ?>
            </a>
            <?php if ($usersConfig->get('allowUserRegistration')) : ?>
                <a class="com-users-login__register list-group-item" href="<?php echo Route::_('index.php?option=com_users&view=registration'); ?>">
                    <?php echo Text::_('COM_USERS_LOGIN_REGISTER'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
