<?php
/*
 * @package    simple_blank_template
 * @version 6.0.0-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 14:28
 */

/*
 * File scripter.php is programmed
 * specially for Simple Blank Template by
 * Ilya A.Zhulin <ilya.zhulin@hotmail.com> 2021
 */

namespace SimpleBlank\Site\Field;
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

/**
 * Поле-триггер для создания структуры темы и генерации конфигов
 * @since   6.0.0
 */
class ScripterField extends FormField
{

	/**
	 * @var string Тип поля
	 */
	protected $type = 'scripter';

	/**
	 * @var bool Скрытое поле
	 */
	protected $hidden = true;

	/**
	 * Основная логика
	 *
	 * @return string Пустая строка (логика выполняется при загрузке формы)
	 */
	protected function getInput()
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		// Получаем ID стиля (шаблона)
		$styleId = $input->getInt('id', 0);
		if (!$styleId)
		{
			return '';
		}

		$db = Factory::getContainer()->get(DatabaseInterface::class);
		// 1. Безопасный запрос параметров шаблона
		$query = $db->getQuery(true)
			->select($db->quoteName('params'))
			->from($db->quoteName('#__template_styles'))
			->where($db->quoteName('id') . ' = :id')
			->bind(':id', $styleId, ParameterType::INTEGER);

		$db->setQuery($query);
		$paramsJson = $db->loadResult();

		if (!$paramsJson)
		{
			return '';
		}

		$params = json_decode($paramsJson);

		// --- ЛОГИКА 1: Создание новой темы ---
		if (!empty($params->themename) && strlen(trim($params->themename)) > 0)
		{
			$themeName = trim(preg_replace('/[^A-Za-z0-9_-]/', '', $params->themename)); // Санитизация имени
			$themePath = JPATH_ROOT . '/templates/simple_blank/themes/' . $themeName;

			// Создаем структуру папок
			Folder::create($themePath);
			Folder::create($themePath . '/less');
			Folder::create($themePath . '/css');
			Folder::create($themePath . '/js');
			Folder::create($themePath . '/images');
			Folder::create($themePath . '/fonts');
			Folder::create($themePath . '/html');

			// Шаблон контента файлов
			$lessComment = "/**\n * File created for theme {$themeName}\n * in Simple Blank template\n * Put your less here.\n **/\n";
			$cssComment  = "/**\n * File created for theme {$themeName}\n * in Simple Blank template\n * Put your css here.\n **/\n";
			$templateCss  = "@import \"../../../../../media/templates/site/simple_blank/vendor/uikit/css/uikit.css\";\n\n{$cssComment}";
			$jsComment   = "/**\n * File created for theme {$themeName}\n * in Simple Blank template\n * Put your js here.\n **/\n";
			$phpComment  = "<?php\n/**\n * File created for theme {$themeName}\n * in Simple Blank template\n * Put your code here.\n **/\n";
			$blockMsg    = "<h1>&#128683; You are not welcome here</h1>";

			$files = [
				'index.html'             => $blockMsg,
				'less/index.html'        => $blockMsg,
				'css/index.html'         => $blockMsg,
				'js/index.html'          => $blockMsg,
				'images/index.html'      => $blockMsg,
				'fonts/index.html'       => $blockMsg,
				'html/index.html'        => $blockMsg,
				"less/{$themeName}.less" => $lessComment,
				'css/template.css'       => $templateCss,
				"css/{$themeName}.css"   => $cssComment,
				"js/{$themeName}.js"     => $jsComment,
				'head_top.php'           => $phpComment,
				'head_bottom.php'        => $phpComment,
				'footer.php'             => $phpComment,
			];

			foreach ($files as $file => $content)
			{
				File::write($themePath . '/' . $file, $content);
			}

			// Удаляем themename из параметров, чтобы не создавать тему повторно при каждом сохранении
			unset($params->themename);
			$newParamsJson = json_encode($params);

			// Обновляем параметры в БД
			$updateQuery = $db->getQuery(true)
				->update($db->quoteName('#__template_styles'))
				->set($db->quoteName('params') . ' = :params')
				->where($db->quoteName('id') . ' = :id')
				->bind(':params', $newParamsJson)
				->bind(':id', $styleId, ParameterType::INTEGER);

			$db->setQuery($updateQuery);
			$db->execute();

			$app->enqueueMessage('Тема "' . $themeName . '" успешно создана. Выберите её в списке.', 'success');

			// Редирект для очистки POST данных и перезагрузки формы с новым списком тем
			$app->redirect(Uri::getInstance()->toString());

			return ''; // Выполнение прекращается после redirect, но на всякий случай возвращаем пустоту
		}

		// --- ЛОГИКА 2: Генерация конфигов при выборе темы ---
		if (!empty($params->theme_select) && strlen(trim($params->theme_select)) > 0)
		{
			$selectedTheme = trim($params->theme_select);
			$rootPath      = JPATH_ROOT . '/media/templates/site/simple_blank/';
			$tplRootPath   = JPATH_ROOT . '/templates/simple_blank/';

			// Пути к файлам темы
			$themeLessPath = '../../../../../templates/simple_blank/themes/' . $selectedTheme . '/less/' . $selectedTheme . '.less';
			$themeHeadTop  = '/templates/simple_blank/themes/' . $selectedTheme . '/head_top.php';
			$themeHeadBot  = '/templates/simple_blank/themes/' . $selectedTheme . '/head_bottom.php';
			$themeFooter   = '/templates/simple_blank/themes/' . $selectedTheme . '/footer.php';

			// 1. Генерация template.less
			$templateLessSrc = $rootPath . 'less/template.tmp';
			$templateLessDst = $rootPath . 'less/template.less';

			if (File::exists($templateLessSrc))
			{
				$content = file_get_contents($templateLessSrc);
				$content = str_replace('path_to_theme_file', $themeLessPath, $content);
				File::write($templateLessDst, $content);
			}

			// 2. Генерация head.php (сложная логика с двумя заменами в оригинале, оптимизируем)
			// В оригинале сначала читался head.tmp -> заменялся top -> сохранялся в head.php
			// Затем читался head.php -> заменялся bottom -> сохранялся в head.php
			// Сделаем это аккуратнее:

			$headTmpSrc = $tplRootPath . 'includes/head.tmp';
			$headDst    = $tplRootPath . 'includes/head.php';

			if (File::exists($headTmpSrc))
			{
				$content = file_get_contents($headTmpSrc);
				// Замена 1: Top
				$content = str_replace('path_to_theme_file1', $themeHeadTop, $content);
				// Замена 2: Bottom (ищем второй плейсхолдер, если он есть, или используем тот же подход)
				// В оригинале второй replace искал 'path_to_theme_file2'. Убедимся, что он есть в tmp файле.
				$content = str_replace('path_to_theme_file2', $themeHeadBot, $content);

				File::write($headDst, $content);
			}

			// 3. Генерация footer.php
			$footerTmpSrc = $tplRootPath . 'includes/footer.tmp';
			$footerDst    = $tplRootPath . 'includes/footer.php';

			if (File::exists($footerTmpSrc))
			{
				$content = file_get_contents($footerTmpSrc);
				$content = str_replace('path_to_theme_file', $themeFooter, $content);
				File::write($footerDst, $content);
			}
		}

		// Поле скрытое, ничего не рендерим
		return '';
	}
}
