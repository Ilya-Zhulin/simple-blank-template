<?php

/*
 * @package    simple_blank_template
 * @version    __DEPLOY_VERSION__
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 13:20
 */

// Пространство имен должно соответствовать пути:
// templates/simpleblank/includes/fields -> Template\Simpleblank\Field
// Замените 'Simpleblank' на название вашего шаблона с большой буквы без пробелов
namespace SimpleBlank\Site\Field;

use Joomla\CMS\Form\Field\TextField;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

/**
 * Поле для создания имени темы.
 * Наследуемся от TextField, так как это текстовое поле с кнопкой.
 */
class ThemenameField extends TextField
{
    /**
     * Тип поля. Должен совпадать с именем класса без суффикса "Field" и в нижнем регистре (обычно).
     * Но так как мы используем addfieldprefix, Joomla будет искать класс по имени.
     */
    protected $type = 'Themename';

    /**
     * Метод получения HTML ввода
     *
     * @return  string  HTML строка
     */
    protected function getInput()
    {
        // Используем современный Text вместо JText
        $labelCreate = Text::_('COM_TEMPLATES_THEMES_CREATE_FIELDSET_LABEL');

        // Рендеринг основного инпута через лейаут (стандарт Joomla 4/5)
        $html = '<div class="input-group">'; // В J4/5 лучше input-group, чем input-append (Bootstrap 5)
        $html .= $this->getRenderer($this->layout)->render($this->getLayoutData());

        // Кнопка. Обратите внимание: классы кнопок в J4/5 изменились (btn -> btn-secondary и т.д.)
        // Если у вас UIKit, классы могут быть своими, но для админки Joomla оставим стандарт илиUIKit классы, если подключены.
        $html .= '<button class="btn btn-secondary" type="button" onclick="Joomla.submitbutton(\'style.apply\');">';
        $html .= $labelCreate;
        $html .= '</button>';
        $html .= '</div>';

        return $html;
    }
}
