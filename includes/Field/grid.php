<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Подключение требуемых файлов
jimport('joomla.form.formfield');

/**
 * Создаем класс.
 */
class JFormFieldGrid extends JFormField {

    /**
     * @var $type    Имя типа
     */
    protected $type = 'Grid';

    /**
     * Метод, определяющий что будет выводить параметр
     *
     * @return    Результат вывода типа
     */
    protected function getInput() {
        $html = '';
        $source_dir = '../templates/simple_blank/layouts';
        if (file_exists($source_dir) == true) {
            $source_items = scandir($source_dir);
            $k = 0;
            $html.='<select id="' . $this->id . '_layout" name="' . $this->name . '[layout]">';
            foreach ($source_items as $item) {
                if (is_file($source_dir . '/' . $item) && fnmatch("*.php", $item)) {
                    $k++;
                    $fname = str_replace('.php', '', $item);
                    $selected = '';
                    if (isset($this->value['layout']) && $this->value['layout'] == $fname) {
                        $selected = ' selected';
                    }
                    $html.='<option value="' . $fname . '"' . $selected . '>' . ucfirst($fname) . '</option>';
                }
            }
            $html.='</select>';
            $html.='<select id="' . $this->id . '_responsive" name="' . $this->name . '[responsive]">';
            $html.='<option value="">No responsive</option>';
            $html.='<option value="medium"' . ((isset($this->value['responsive']) && $this->value['responsive'] === 'medium') ? ' selected' : '') . '>Stacked on phones</option>';
            $html.='<option value="large"' . ((isset($this->value['responsive']) && $this->value['responsive'] === 'large') ? ' selected' : '') . '>Stacked on tablets and phones</option>';
            $html.='</select>';
            if ($k == 0) {
                $html = 'Put your Layout files to layouts folder';
            }
        } else {
            echo 'sourcedirnok';   //Проверка наличия каталога-источника - неудачно
        }
        return $html;
    }

}
