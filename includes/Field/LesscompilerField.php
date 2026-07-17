<?php
/*
 * @package    simple_blank_template
 * @version 3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 13:36
 */

/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 */

namespace SimpleBlank\Site\Field;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\Database\DatabaseInterface;

/**
 * Поле кнопки для компиляции LESS (или других действий)
 */
class LesscompilerField extends FormField
{

    /**
     * @var string Тип поля
     */
    protected $type = 'lesscompiler';

    /**
     * Метод, определяющий что будет выводить параметр
     *
     * @return string HTML код кнопки
     */
    protected function getInput()
    {
        // Получаем объект базы данных через контейнер (стандарт J4/J5)
        $db = Factory::getContainer()->get(DatabaseInterface::class);

        // Формируем запрос
        $query = $db->getQuery(true)
            ->select($db->quoteName('extension_id'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('template'))
            ->where($db->quoteName('name') . ' = ' . $db->quote('simple_blank')); // Внимание: проверь имя шаблона!

        $db->setQuery($query);
        $ext_id = $db->loadResult();

        // Собираем атрибуты
        $class = $this->getAttribute('class', '');
        $id = $this->id;
        $onclick = $this->getAttribute('onclick', '');
        $onClickAttr = $onclick ? ' onclick="' . $onclick . '"' : '';

        // Тексты
        $title = ' title="' . Text::_('TPL_SIMPLE_BLANK_LESS_COMPILE_BUTTON_DESCRIPTION') . '"';
        $label = Text::_('TPL_SIMPLE_BLANK_LESS_COMPILE_BUTTON_CAPTION');

        // Формируем HTML
        $html = '<button type="button" id="' . $id . '" class="' . $class . '"' . $onClickAttr . $title . ' data-extension-id="' . $ext_id . '">';
        $html .= $label;
        $html .= '</button>';

        return $html;
    }

}
