<?php
/*
 * @package    simple_blank_template
 * @version    3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 */

namespace SimpleBlank\Site\Field;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;

/**
 * Скрытое поле для загрузки JS админки (LESS компиляция, ширины, навигация по позициям)
 */
class PositionnavField extends FormField
{
    protected $type = 'positionnav';

    protected function getInput()
    {
        $doc = Factory::getApplication()->getDocument();
        $doc->addScript('../templates/simple_blank/includes/Field/jscript.js');
        $doc->addScript('../templates/simple_blank/includes/Field/position-nav.js');

        return '<input type="hidden" name="' . $this->name . '" value="1">';
    }

    public function renderField($options = array())
    {
        return '<div style="display:none">' . $this->getInput() . '</div>';
    }
}
