<?php
/*
 * @package    simple_blank_template
 * @version 6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 15:07
 */

namespace SimpleBlank\Site\Renderer;

defined('_JEXEC') or die;

class SectionRenderer
{
	protected $template;
	protected $params;

	public function __construct($template, $params)
	{
		$this->template = $template;
		$this->params   = $params;
	}

	public function renderSection($posName, $sectionsData)
	{
		$posName = strtolower($posName);
		$suffix  = str_replace('sb-', '', $posName);

		// Сборка классов и атрибутов секции
		$sectionClass = $suffix;
		$sectionClass .= ' ' . ($this->params->get($suffix . '_addclasses') ?: '');
		$sectionClass .= ' uk-section-' . ($this->params->get($suffix . '_color') ?: 'default');

		$size = $this->params->get($suffix . '_size', 'default');
		if ($size === '0')
		{
			$sectionClass .= ' uk-padding-remove-vertical';
		}
		elseif ($size !== 'default')
		{
			$sectionClass .= ' uk-section-' . $size;
		}

		if ($this->params->get($suffix . '_overlap', 0))
		{
			$sectionClass .= ' uk-section-overlap';
		}

		$sectionTag  = $this->params->get($suffix . '_tag', 'section');
		$sectionAttr = ' ' . ($this->params->get($suffix . '_addattr') ?: '');

		$html = '<' . $sectionTag . ' id="' . $posName . '" class="' . trim($sectionClass) . '"' . $sectionAttr . '>';

		// Контейнер секции
		$container = $this->params->get($suffix . '_container', 0);
		if ($container != 0)
		{
			$html .= '<div class="uk-container';
			if ($container == 1)
			{
				$html .= ' uk-container-center';
			}

			$cWidth = $this->params->get($suffix . '_container_width', 'max');
			if ($cWidth !== 'max')
			{
				$html .= ' uk-container-' . $cWidth;
			}
			$html .= '">';
		}

		// Рендеринг позиций внутри секции (логика из _buildPosition)
		if (isset($sectionsData[$posName]) && is_array($sectionsData[$posName]))
		{
			foreach ($sectionsData[$posName] as $item)
			{
				$html .= $this->renderPositionItem($item, $posName);
			}
		}
		else
		{
			// Fallback для статических позиций, если нет данных из subform
			$html .= '<jdoc:include type="modules" name="' . $posName . '" />';
		}

		if ($container != 0)
		{
			$html .= '</div>';
		}

		$html .= '</' . $sectionTag . '>';

		return $html;
	}

	protected function renderPositionItem($item, $parentPosName)
	{
		$posName       = strtolower($posName);
		$suffix        = str_replace('sb-', '', $posName);
		$section_class = $suffix;
		$section_class .= isset($params[$suffix . '_addclasses']) ? ' ' . $params[$suffix . '_addclasses'] : '';
		$section_class .= isset($params[$suffix . '_color']) ? ' uk-section-' . $params[$suffix . '_color'] : '';
		$section_attr  = isset($params[$suffix . '_addattr']) ? ' ' . $params[$suffix . '_addattr'] : '';
		$section_tag   = isset($params[$suffix . '_tag']) ? $params[$suffix . '_tag'] : 'section';
		if (isset($params[$suffix . '_size']))
		{
			switch ($params[$suffix . '_size'])
			{
				case 'default':
					$section_class .= '';
					break;
				case '0':
					$section_class .= ' uk-padding-remove-vertical';
					break;
				default:
					$section_class .= ' uk-section-' . $params[$suffix . '_size'];
			}
		}
		$section_class .= (isset($params[$suffix . '_overlap']) && $params[$suffix . '_overlap'] == '1') ? ' uk-section-overlap' : '';
		$out           = '<' . $section_tag . ' id="' . $posName . '" class="' . $section_class . '"' . $section_attr . '>';

		if (isset($params[$suffix . '_container']) && $params[$suffix . '_container'] !== '0')
		{
			$out .= '<div class="uk-container';
			if ($params[$suffix . '_container'] == '1')
			{
				$out .= ' uk-container-center';
			}
			switch ($params[$suffix . '_container_width'])
			{
				case 'max':
					break;
				default:
					$out .= ' uk-container-' . $params[$suffix . '_container_width'];
					break;
			}
			$out .= '">';
		}
		if (isset($sections[$posName]) || isset($sections[$posName . '-left']) || isset($sections[$posName . '-right']) || isset($sections[$posName . '-center']))
		{
			foreach ($sections[$posName] as $section_item)
			{
				if (is_array($section_item))
				{
					$pos_name = strtolower($section_item["pos-name"]);
					if ($template->countModules($pos_name) && isset($section_item['pos-container']) && $section_item['pos-container'] > 0)
					{
						$out .= '<div class="uk-container';
						if ($section_item['pos-container'] == 1)
						{
							$out .= ' uk-container-center';
						}
						$out .= ' uk-container-' . $section_item['pos-container_width'];
						$out .= strlen(trim($section_item['pos-container-addclasses'])) > 0 ? ' ' . trim($section_item['pos-container-addclasses']) : '';
						$out .= '"';
						$out .= strlen(trim($section_item['pos-container-addparams'])) > 0 ? ' ' . trim($section_item['pos-container-addparams']) : '';
						$out .= '>';
					}
					if ($template->countModules($section_item["pos-name"]) ||
						(
							isset($section_item['pos-navbar']) && ($template->countModules($section_item["pos-name"] . '-left') ||
								$template->countModules($section_item["pos-name"] . '-center') ||
								$template->countModules($section_item["pos-name"] . '-right'))
						)
					)
					{
						if (isset($section_item['pos-sticky']))
						{
							$out .= '<div id="' . $pos_name . '-sticky" uk-sticky="' . $section_item['pos-sticky-params'] . '">';
						}
						if (isset($section_item['pos-dropdown']) && $section_item['pos-dropdown'] > 0)
						{
							$out .= '<div id="' . $section_item['pos-name'] . '-dropdown" uk-dropdown="' . $section_item['pos-dropdown-params'] . '" class="' . $section_item['pos-dropdown-addclasses'] . '">';
						}
						if (isset($section_item['pos-modal']) && $section_item['pos-modal'] > 0)
						{
							$modal_class = "";
							$modal_class .= ($section_item['pos-modal-center'] > 0) ? "uk-flex-top" : "";
							$modal_class .= (strlen($modal_class) > 0) ? " " : "";
							$modal_class .= 'uk-modal-' . $section_item['pos-modal-size'];
							$modal_class .= (strlen($modal_class) > 1) ? " " : "";
							$modal_class .= $section_item['pos-modal-addclasses-modal'];
							$out         .= '<div id="' . $section_item['pos-name'] . '-modal" uk-modal="' . $section_item['pos-modal-params'] . '" class="' . $modal_class . '" ' . $section_item['pos-modal-addparams-modal'] . '>';
							$out         .= '<div class="' . $section_item['pos-modal-addclasses-dialog'] . '" ' . $section_item['pos-modal-addparams-dialog'] . '>';
							if ($section_item['pos-modal-close'] > 0)
							{
								$close_class = '';
								$close_class .= "uk-modal-close-" . $section_item['pos-modal-close-pos'];
								$close_class .= ($section_item['pos-modal-close-size'] != 'default') ? " uk-close-" . $section_item['pos-modal-close-size'] : "";
								$close_tag   = ($section_item['pos-modal-close-tag'] == 'a') ? 'a href=""' : 'button type="button"';
								$out         .= '<' . $close_tag . ' uk-close class="' . $close_class . '"></' . $section_item['pos-modal-close-tag'] . '>';
							}
						}
						if (isset($section_item['pos-navbar']) || isset($section_item['pos-grid']))
						{
							if (isset($section_item['pos-navbar']) && $section_item['pos-navbar'] > 0)
							{
								$out .= '<nav id="' . $pos_name . '-navbar" class="uk-navbar-container';
								if (isset($section_item['pos-navbar-transparent']))
								{
									$out .= ' uk-navbar-transparent';
								}
								if (isset($section_item['pos-navbar-addclasses']))
								{
									$out .= ' ' . $section_item['pos-navbar-addclasses'];
								}
								if (isset($section_item['pos-navbar-container']) && $section_item['pos-navbar-container'] !== 'none')
								{
									$out .= '">';
									$out .= '<div class="uk-container';
									if ($section_item['pos-navbar-container'] !== 'default')
									{
										$out .= ' uk-container-' . $section_item['pos-navbar-container'];
									}
									$out .= '">';
									$out .= '<div uk-navbar="' . $section_item['pos-navbar-params'] . '">';
								}
								else
								{
									$out .= '" uk-navbar="' . $section_item['pos-navbar-params'] . '">';
								}
							}
							if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1')
							{
								$grid_params    = (isset($section_item['pos-grid-params']) && strlen($section_item['pos-grid-params']) > 0) ? $section_item['pos-grid-params'] : '';
								$grid_addparams = (isset($section_item['pos-grid-addparams']) && strlen($section_item['pos-grid-addparams']) > 0) ? ' ' . $section_item['pos-grid-addparams'] : '';
								$grid_class     = '';
								$grid_class     .= ((isset($section_item['pos-grid-gap-h']) && isset($section_item['pos-grid-gap-v'])) && ($section_item['pos-grid-gap-h'] != $section_item['pos-grid-gap-v'])) ? ' uk-grid-column-' . $section_item['pos-grid-gap-h'] . ' uk-grid-row-' . $section_item['pos-grid-gap-v'] : '';
								$grid_class     .= ((isset($section_item['pos-grid-gap-h']) && isset($section_item['pos-grid-gap-v'])) && ($section_item['pos-grid-gap-h'] == $section_item['pos-grid-gap-v'])) ? ' uk-grid-' . $section_item['pos-grid-gap-h'] : '';
								$grid_class     .= (isset($section_item['pos-grid-divider']) && $section_item['pos-grid-divider'] > 0) ? ' uk-grid-divider' : '';
								$grid_class     .= (isset($section_item['pos-grid-addclasses']) && strlen($section_item['pos-grid-addclasses']) > 0) ? ' ' . $section_item['pos-grid-addclasses'] : '';
								$grid_class     = (strlen(trim($grid_class)) > 0) ? ' class="' . $grid_class . '"' : '';
								$out            .= '<div uk-grid="' . $grid_params . '"';
								$out            .= $grid_addparams;
								$out            .= $grid_class;
								$out            .= '>';
							}
							if ($template->countModules($section_item["pos-name"] . '-left'))
							{
								$out .= '<div class="uk-navbar-left">';
								$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-left" />';
								$out .= '</div>';
							}
							if ($template->countModules($section_item["pos-name"] . '-center'))
							{
								$out .= '<div class="uk-navbar-center">';
								$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-center" />';
								$out .= '</div>';
							}
							if ($template->countModules($section_item["pos-name"] . '-right'))
							{
								$out .= '<div class="uk-navbar-right">';
								$out .= '<jdoc:include type="modules" name="' . $section_item["pos-name"] . '-right" />';
								$out .= '</div>';
							}
						}
						$out .= '<jdoc:include type="modules" name="' . $pos_name . '" />';
						if ($template->countModules($pos_name) && isset($section_item['pos-container']) && $section_item['pos-container'] > 0)
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-grid']) && $section_item['pos-grid'] == '1')
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-navbar-center']) && $section_item['pos-navbar-center'] === '1')
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-navbar-container']) && $section_item['pos-navbar-container'] !== 'none')
						{
							$out .= '</div>';
							$out .= '</div>';
						}
						if (isset($section_item['pos-navbar']))
						{
							$out .= '</nav>';
						}
						if (isset($section_item['pos-modal']) && $section_item['pos-modal'] > 0)
						{
							$out .= '</div>';
							$out .= '</div>';
						}
						if (isset($section_item['pos-dropdown']) && $section_item['pos-dropdown'] > 0)
						{
							$out .= '</div>';
						}
						if (isset($section_item['pos-sticky']))
						{
							$out .= '</div>';
						}
					}
				}
			}
		}
		else
		{
			$out .= '<jdoc:include type="modules" name="' . $posName . '" />';
		}
		if (isset($params[$suffix . '_container']) && $params[$suffix . '_container'] !== '0')
		{
			$out .= '</div>';
		}
		$out .= '</' . $section_tag . '>';

		return '<!-- Rendered Item: ' . $item['pos-name'] . ' -->';
	}
}
