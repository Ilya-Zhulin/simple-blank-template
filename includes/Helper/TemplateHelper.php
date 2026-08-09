<?php
/*
 * @package    simple_blank_template
 * @version 3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 15:05
 */

namespace SimpleBlank\Site\Helper;

defined('_JEXEC') or die;

class TemplateHelper
{
	protected $params;
	protected $template;

	public function __construct($params, $template)
	{
		$this->params   = $params;
		$this->template = $template;
	}

	// Твоя функция расчета дробей (gcf и fraction)
	public function getFraction($nominator, $divider = 60)
	{
		$gcf = function ($a, $b) use (&$gcf) {
			return ($b > 0) ? $gcf($b, $a % $b) : $a;
		};

		return $nominator / ($factor = $gcf($nominator, $divider)) . '-' . $divider / $factor;
	}

	// Логика подготовки массива $sections из твоего config.php
	public function prepareSections()
	{
		$sections                       = [];
		$sections['sb-main']['isExist'] = 1;

		// Читаем новое поле subform positions-location
		$positions = (array) $this->params->get('positions-location');

		if (is_array($positions) && count($positions) > 0)
		{
			foreach ($positions as $posid => $position)
			{
				$position = (array) $position;
				$posName  = (string) ($position['pos-name'] ?? '');

				// Пропускаем позиции, в названии которых нет ни одной буквы
				if (!preg_match('/\p{L}/u', $posName))
				{
					continue;
				}

				$secName  = strtolower((string) ($position['pos-section'] ?? ''));

				if (!isset($sections[$secName]))
				{
					$sections[$secName]            = [];
					$sections[$secName]['isExist'] = 0;
				}

				$sections[$secName][] = $position;

				if (strlen($posName) > 0)
				{
					$hasModule = $this->template->countModules($posName) > 0
						|| $this->template->countModules($posName . '-left')
						|| $this->template->countModules($posName . '-right')
						|| $this->template->countModules($posName . '-center');

					if ($hasModule)
					{
						$sections[$secName]['isExist'] = 1;
					}
				}
			}
		}

		return $sections;
	}

	// Подготовка данных сайдбара (возвращает массив вместо кучи переменных)
	public function getSidebarData($prefix)
	{
		$show = $this->params->get($prefix . '_show');

		return [
			'show'       => $show,
			'tag'        => $this->params->get($prefix . '_tag'),
			'position'   => $this->params->get($prefix . '_position'),
			'width'      => $show ? $this->params->get($prefix . '_width') : 0,
			'height'     => $show ? $this->params->get($prefix . '_height') : 1,
			'classes'    => $show ? ' ' . $this->params->get($prefix . '_addclasses') : '',
			'attrs'      => $show ? ' ' . $this->params->get($prefix . '_addattr') : '',
			'real_width' => 0
		];
	}

	// Подготовка данных Offcanvas
	public function getOffcanvasData($prefix)
	{
		$show = $this->params->get($prefix . '_show');
		$pos  = $this->params->get($prefix . '_position');

		return [
			'show'        => $show,
			'tag'         => $this->params->get($prefix . '_tag'),
			'position'    => $pos,
			'animation'   => $this->params->get($prefix . '_animation'),
			'flip'        => ($pos == '1') ? 'false' : 'true',
			'overlay'     => $this->params->get($prefix . '_overlay'),
			'close'       => $this->params->get($prefix . '_close_button'),
			'close_large' => $this->params->get($prefix . '_close_button_large'),
			'classes'     => $this->params->get($prefix . '_addclasses'),
			'attrs'       => $this->params->get($prefix . '_addattr'),
			'bar_classes' => $this->params->get($prefix . '_bar_addclasses'),
			'bar_attrs'   => $this->params->get($prefix . '_bar_addattr'),
		];
	}
}
