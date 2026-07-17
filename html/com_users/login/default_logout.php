<?php
/*
 * @package    simple_blank_template
 * @version 3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 17:15
 */

defined('_JEXEC') or die;

use SimpleBlank\Site\Service\ThemeManager;

// Одна строка магии
if ($themeFile = ThemeManager::checkThemeOverride(__FILE__))
{
    include $themeFile;

    return;
}

// Дальше стандартный код...

?>
<div class="logout<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        </div>
    <?php endif; ?>
    <?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '') || $this->params->get('logout_image') != '') : ?>
    <div class="logout-description">
        <?php endif; ?>
        <?php if ($this->params->get('logoutdescription_show') == 1) : ?>
            <?php echo $this->params->get('logout_description'); ?>
        <?php endif; ?>
        <?php if ($this->params->get('logout_image') != '') : ?>
            <img src="<?php echo $this->escape($this->params->get('logout_image')); ?>"
                 class="thumbnail pull-right logout-image" alt="<?php echo JText::_('COM_USER_LOGOUT_IMAGE_ALT'); ?>"/>
        <?php endif; ?>
        <?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '') || $this->params->get('logout_image') != '') : ?>
    </div>
<?php endif; ?>
    <form action="<?php echo JRoute::_('index.php?option=com_users&task=user.logout'); ?>" method="post"
          class="form-horizontal well">
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary">
                    <span class="icon-arrow-left icon-white"></span>
                    <?php echo JText::_('JLOGOUT'); ?>
                </button>
            </div>
        </div>
        <?php if ($this->params->get('logout_redirect_url')) : ?>
            <input type="hidden" name="return"
                   value="<?php echo base64_encode($this->params->get('logout_redirect_url', $this->form->getValue('return'))); ?>"/>
        <?php else : ?>
            <input type="hidden" name="return"
                   value="<?php echo base64_encode($this->params->get('logout_redirect_menuitem', $this->form->getValue('return'))); ?>"/>
        <?php endif; ?>
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
