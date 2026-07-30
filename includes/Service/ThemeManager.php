<?php
/*
 * @package    simple_blank_template
 * @version 3.0.2-dev
 * @author     Ilya A.Zhulin <ilya.zhulin@hotmail.com>
 * @copyright  ©Ilya A.Zhulin, 2026
 * @license    GNU General Public License version 2 or later;
 *
 * The last change: 16.03.2026, 17:38
 */

namespace SimpleBlank\Site\Service;

use InvalidArgumentException;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;

defined('_JEXEC') or die;

/**
 * Class ThemeManager
 *
 * Центральный сервис для управления темами шаблона, путями и переопределениями.
 */
class ThemeManager
{
	/**
	 * @var ?ThemeManager Экземпляр синглтона
	 */
	private static ?ThemeManager $instance = null;

	/**
	 * @var string Имя текущего шаблона
	 */
	private string $templateName;

	/**
	 * @var string Базовый путь к шаблону (JPATH_THEMES/...)
	 */
	private string $templateBasePath;

	/**
	 * @var ?string Имя активной темы (из параметра theme_select)
	 */
	private ?string $activeThemeName = null;

	/**
	 * Конструктор (защищен для паттерна Синглтон)
	 */
	private function __construct()
	{
		$app = Factory::getApplication();

		// Получаем имя шаблона
		$this->templateName     = $app->getTemplate();
		$this->templateBasePath = JPATH_THEMES . '/' . $this->templateName;

		// Получаем параметры шаблона (нужен объект template с params)
		$templateObject = $app->getTemplate(true);

		// Читаем параметр выбора темы (согласно templateDetails.xml: name="theme_select")
		$themeParam = $templateObject->params->get('theme_select', '');

		// Если тема выбрана и не пустая, сохраняем имя
		$this->activeThemeName = empty($themeParam) ? null : trim($themeParam);
	}

	/**
	 * Магическая функция для проверки наличия переопределения файла в активной теме.
	 * Используется внутри файлов оверрайдов компонентов/модулей/лейаутов.
	 *
	 * Логика работы:
	 * 1. Принимает полный путь текущего файла (__FILE__).
	 * 2. Вычисляет относительный путь относительно папки /html/ шаблона.
	 * 3. Проверяет существование аналогичного файла в папке /themes/[THEME]/html/.
	 * 4. Возвращает полный путь к файлу в теме, если он существует.
	 * 5. Возвращает false, если темы нет или файл в теме не найден.
	 *
	 * Использование в коде оверрайда:
	 * if ($themeFile = ThemeManager::checkThemeOverride(__FILE__)) {
	 *     include $themeFile;
	 *     return;
	 * }
	 *
	 * @param   string  $currentFile  Полный путь к текущему файлу (передавайте магическую константу __FILE__)
	 *
	 * @return string|false Полный абсолютный путь к файлу в теме, если найден. Иначе false.
	 */
	public static function checkThemeOverride(string $currentFile)
	{
		$manager = self::getInstance();

		// Если тема не активна, поиск не имеет смысла
		if (!$manager->hasActiveTheme())
		{
			return false;
		}

		$templateBase = $manager->templateBasePath;
		$htmlBase     = $templateBase . '/html/';

		// Нормализуем слэши для кроссплатформенности (Windows/Linux)
		$currentFile = str_replace('\\', '/', $currentFile);
		$htmlBase    = str_replace('\\', '/', $htmlBase);

		// Убеждаемся, что файл действительно лежит в папке html нашего шаблона
		// Это защита от случайного вызова в неправильном контексте
		if (strpos($currentFile, $htmlBase) !== 0)
		{
			return false;
		}

		// Вычисляем относительный путь от папки /html/
		// Пример: было /var/www/templates/simple_blank/html/com_content/article/default.php
		// Стало: com_content/article/default.php
		$relativePath = substr($currentFile, strlen($htmlBase));

		// Формируем предполагаемый путь в теме:
		// /templates/simple_blank/themes/[THEME]/html/[RELATIVE_PATH]
		$themeFilePath = $manager->getThemeBasePath() . '/html/' . $relativePath;

		// Проверяем физическое наличие файла
		if (file_exists($themeFilePath))
		{
			return $themeFilePath;
		}

		return false;
	}

	/**
	 * Получить единственный экземпляр класса (Синглтон)
	 *
	 * @return ThemeManager
	 */
	public static function getInstance(): ThemeManager
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Проверить, активна ли какая-либо тема
	 *
	 * @return bool True если тема выбрана
	 */
	public function hasActiveTheme(): bool
	{
		return (!is_null($this->activeThemeName) && !is_null($this->getThemeBasePath()));
	}

	/**
	 * Получить базовый путь к папке активной темы
	 * Пример: /var/www/site/templates/simple_blank/themes/my-theme
	 *
	 * @return string|null Путь или null, если тема не активна
	 */
	public function getThemeBasePath($name = null): ?string
	{
		$themeName = is_null($name) ? $this->activeThemeName : $name;

		return Folder::exists($this->templateBasePath . '/themes/' . $themeName) ? $this->templateBasePath . '/themes/' . $themeName : null;
	}

	/**
	 * Зарегистрировать глобальные константы для удобного доступа к путям темы.
	 * Вызывать один раз после инициализации менеджера.
	 *
	 * Определенные константы:
	 * SB_THEME_NAME       - Имя активной темы (или false, если нет)
	 * SB_THEME_PATH       - Полный путь к папке темы на сервере (или false)
	 * SB_THEME_URL        - URL к папке темы (или false)
	 * SB_THEME_HAS_ACTIVE - Boolean: активна ли тема вообще
	 */
	public static function registerConstants(): void
	{
		$manager = self::getInstance();

		// Имя темы
		if (!defined('SB_THEME_NAME'))
		{
			define('SB_THEME_NAME', $manager->getActiveTheme() ?: false);
		}

		// Флаг активности
		if (!defined('SB_THEME_HAS_ACTIVE'))
		{
			define('SB_THEME_HAS_ACTIVE', $manager->hasActiveTheme());
		}

		// Путь к папке темы
		if (!defined('SB_THEME_PATH'))
		{
			$path = $manager->getThemeBasePath();
			define('SB_THEME_PATH', $path ?: false);
		}

		// URL к папке темы
		if (!defined('SB_ROOT'))
		{
			define('SB_ROOT', $manager->templateBasePath);
		}
	}

	/**
	 * Получить имя активной темы
	 *
	 * @return string|null Имя темы или null, если не выбрана
	 */
	public function getActiveTheme(): ?string
	{
		return $this->activeThemeName;
	}

	/**
	 * Отрендерить секцию-лейаут с учетом приоритетов (Тема -> Глобальный -> Дефолт)
	 *
	 * @param   string  $sectionName  Имя секции: 'top', 'main', 'bottom'
	 * @param   array   $data         Данные для передачи в лейаут (sections, config и т.д.)
	 *
	 * @return string             HTML-вывод секции
	 */
	public static function renderSection(string $sectionName, array $data = []): string
	{
		$manager = self::getInstance();

		// Формируем список путей для поиска в правильном порядке
		$paths = [];

		// 1. Путь темы (если активна)
		if ($manager->hasActiveTheme())
		{
			$themePath = $manager->getThemeBasePath() . '/html/layouts/section';
			if (is_dir($themePath))
			{
				$paths[] = $themePath;
			}
		}

		// 2. Глобальный оверрайд шаблона
		$globalOverride = JPATH_THEMES . '/simple_blank/html/layouts/section';
		if (is_dir($globalOverride))
		{
			$paths[] = $globalOverride;
		}

		// 3. Дефолтный путь
		$paths[] = JPATH_THEMES . '/simple_blank/includes/layouts/section';

		// Инициализируем лейаут с массивом путей
		$layout = new \Joomla\CMS\Layout\FileLayout($sectionName, $paths);

		return $layout->render($data);
	}

	/**
	 * Статический хелпер для быстрого получения пути (обертка над getInstance()->findFile)
	 *
	 * @param   string  $type          Тип ресурса
	 * @param   string  $relativePath  Относительный путь
	 *
	 * @return string|false
	 */
	public static function getPath(string $type, string $relativePath)
	{
		return self::getInstance()->findFile($type, $relativePath);
	}

	/**
	 * Найти полный путь к файлу ресурса (layout, css, js, image) с учетом приоритетов.
	 * Приоритет: Тема -> Глобальный оверрайд -> Дефолт.
	 *
	 * @param   string  $type          Тип ресурса: 'layout', 'css', 'js', 'image'
	 * @param   string  $relativePath  Относительный путь (например, 'com_content/article.php' или 'style.css')
	 *
	 * @return string|false Полный путь к найденному файлу или false
	 */
	public function findFile(string $type, string $relativePath)
	{
		$normalizedPath = str_replace('.', '/', $relativePath);

		// Карта путей для разных типов ресурсов
		$pathsConfig = [
			'layout' => [
				'theme'    => '/html/layouts',
				'override' => '/html/layouts',
				'default'  => '/includes/layouts'
			],
			'css'    => [
				'theme'    => '/css',
				'override' => null, // Для CSS обычно нет глобального html оверрайда
				'default'  => '/css'
			],
			'js'     => [
				'theme'    => '/js',
				'override' => null,
				'default'  => '/js'
			],
			'image'  => [
				'theme'    => '/images',
				'override' => null,
				'default'  => '/images'
			]
		];

		if (!isset($pathsConfig[$type]))
		{
			throw new InvalidArgumentException("Unknown resource type: $type");
		}

		$config = $pathsConfig[$type];

		// 1. Проверка в папке ТЕМЫ
		if ($this->activeThemeName && isset($config['theme']))
		{
			$path = $this->getThemeBasePath() . $config['theme'] . '/' . $normalizedPath;
			if (file_exists($path))
			{
				return $path;
			}
		}

		// 2. Проверка ГЛОБАЛЬНОГО ОВЕРРАЙДА шаблона (/html/...)
		if (isset($config['override']) && $config['override'] !== null)
		{
			$path = $this->templateBasePath . $config['override'] . '/' . $normalizedPath;
			if (file_exists($path))
			{
				return $path;
			}
		}

		// 3. Проверка ДЕФОЛТНОЙ папки (/includes/...)
		if (isset($config['default']))
		{
			$path = $this->templateBasePath . $config['default'] . '/' . $normalizedPath;
			if (file_exists($path))
			{
				return $path;
			}
		}

		return false;
	}

	/**
	 * Статический хелпер для быстрого получения URL (обертка над getInstance()->getUrl)
	 *
	 * @param   string  $type          Тип ресурса
	 * @param   string  $relativePath  Относительный путь
	 *
	 * @return string|null
	 */
	public static function getUrlStatic(string $type, string $relativePath): ?string
	{
		return self::getInstance()->getUrl($type, $relativePath);
	}

	/**
	 * Получить URL к файлу ресурса.
	 * Работает аналогично findFile, но возвращает веб-путь.
	 *
	 * @param   string  $type          Тип ресурса
	 * @param   string  $relativePath  Относительный путь
	 *
	 * @return string|null URL файла или null, если не найден
	 */
	public function getUrl(string $type, string $relativePath): ?string
	{
		$filePath = $this->findFile($type, $relativePath);
		if (!$filePath)
		{
			return null;
		}

		// Конвертируем абсолютный путь сервера в URL
		$rootPath        = JPATH_ROOT;
		$relativeUrlPath = str_replace($rootPath, '', $filePath);
		$relativeUrlPath = str_replace('\\', '/', $relativeUrlPath); // Для Windows

		return Uri::root() . ltrim($relativeUrlPath, '/');
	}

	/**
	 * Проверить, включён ли Production Mode.
	 * Читает параметр шаблона production_mode.
	 *
	 * @return bool True если Production Mode активен
	 */
	public function isProductionMode(): bool
	{
		$app = Factory::getApplication();
		$template = $app->getTemplate(true);

		return (bool) $template->params->get('production_mode', 0);
	}

	/**
	 * Скопировать CSS активной темы в корневую папку css/ с префиксом темы.
	 *
	 * @return bool True если копирование выполнено успешно
	 */
	public function productionCopy(): bool
	{
		if (!$this->hasActiveTheme())
		{
			return false;
		}

		$themeCssPath = $this->getThemeBasePath() . '/css/';
		$rootCssPath  = $this->templateBasePath . '/css/';
		$prefix       = 'theme-' . $this->activeThemeName . '-';

		if (!is_dir($themeCssPath))
		{
			return false;
		}

		$copied = false;
		$dh     = opendir($themeCssPath);

		while (($file = readdir($dh)) !== false)
		{
			if ($file === '.' || $file === '..' || $file === 'index.html')
			{
				continue;
			}

			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if ($ext !== 'css')
			{
				continue;
			}

			$srcFile  = $themeCssPath . $file;
			$dstName  = ($file === 'template.css')
				? 'theme-' . $this->activeThemeName . '.css'
				: $prefix . $file;
			$dstFile  = $rootCssPath . $dstName;

			if (copy($srcFile, $dstFile))
			{
				$copied = true;
			}
		}

		closedir($dh);

		return $copied;
	}
}
