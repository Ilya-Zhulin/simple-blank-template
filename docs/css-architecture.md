# CSS/LESS Architecture — Simple Blank Template

## Обзор

Архитектура CSS в шаблоне Simple Blank построена на трёх принципах:
1. **Тема — первый класс citizen**. Всё компилируется в папку темы.
2. **Вендор изолирован**. UIKit не модифицируется напрямую.
3. **Безопасное обновление**. Обновление шаблона не ломает стили активной темы.

---

## Структура файлов

```
templates/simple_blank/
├── css/                                    # Фоллбэк для работы без темы
│   ├── template.css                        # Агрегатор: @import из вендора
│   ├── theme-visparfum.css                 # Production: скопировано из темы (уникальное имя)
│   ├── uikit.css                           # DEPRECATED: старый pre-built (не использовать)
│   └── theme.css                           # DEPRECATED: старый pre-built (не использовать)
│
├── vendor/uikit/css/
│   └── uikit.css                           # Вендорный CSS (фоллбэк, не компилируется)
│
├── themes/visparfum/
│   ├── css/
│   │   └── template.css                    # Development: импорт вендора ИЛИ скомпилированный вывод
│   └── less/
│       ├── variables.less                  # Переопределения переменных
│       ├── card.less                       # Стили компонентов
│       └── ...                             # Остальные файлы темы
│
├── less/
│   ├── template.less                       # Точка входа для LESS-компиляции
│   ├── simple-blank.less                   # Импорт вендорных LESS-файлов
│   └── theme.less                          # Альтернативная точка входа
│
└── includes/
    └── Controller/
        └── ConfigController.php    # Загрузка CSS/JS через WAM (AssetHelper удалён, см. .doc/11-dev-prod-modes.md)
```

---

## Два режима работы

### Режим 1: Без LESS (CSS-only)

Пользователь редактирует CSS-файлы напрямую.

**Подключение:**
```
loadStylesheets() → css/template.css → @import vendor/uikit/css/uikit.css
```

**Что происходит:**
- `template.css` в `css/` содержит `@import url("../vendor/uikit/css/uikit.css");`
- `loadStylesheets()` сканирует `css/`, находит `template.css`, подключает его последним
- Вендорный CSS загружается через @import

### Режим 2: Development (LESS-компиляция)

Пользователь компилирует LESS в браузере (less.js) или через CLI.

**Точка входа:** `less/template.less`

**Порядок импорта:**
```less
// 1. Вендорные переменные (дефолты)
@import '../vendor/uikit/less/uikit.less';

// 2. Темовые переопределения (перезаписывают дефолты ДО компиляции)
@import '../themes/visparfum/less/visparfum.less';
```

**Компиляция:**
```
less/template.less → lessc → themes/visparfum/css/template.css (полный вывод)
```

**Подключение:**
```
loadStylesheets() → themes/visparfum/css/template.css (полный CSS, без @import)
```

### Режим 3: Production

Скомпилированный CSS копируется в `css/` для коротких URL и CDN.

**Копирование (script.php post-install):**
```
themes/visparfum/css/template.css → css/theme-visparfum.css
```

**Подключение:**
```
loadStylesheets() → css/theme-visparfum.css
```

---

## Цикл жизни

### 1. Создание темы

При создании темы:
- В `themes/{theme}/css/` создаётся `template.css` с импортом вендора
- В `less/template.less` прописывается импорт темы

### 2. Разработка

- LESS файлы редактируются в `themes/{theme}/less/`
- Компиляция идёт в `themes/{theme}/css/template.css`
- `loadStylesheets()` подхватывает скомпилированный вывод из темы

### 3. Продакшн

- `script.php` копирует `themes/{theme}/css/template.css` → `css/theme-{theme}.css`
- `loadStylesheets()` грузит из `css/`
- URL короткий: `/templates/simple_blank/css/theme-visparfum.css`

### 4. Обновление шаблона

- `css/` перезаписывается (файлы шаблона восстанавливаются)
- `themes/{theme}/css/` нетронут (Joomla не перезаписывает темы)
- `script.php` снова копирует файлы из темы в `css/`
- Ничего не ломается

---

## Механизм Color Mode (UIkit extend + when guards)

### Проблема

UIkit использует Less `extend` с `when` guards для автоматической адаптации цветов:

```less
// vendor/uikit/less/components/card.less
@card-primary-color-mode: light;  // дефолт

// when guard: если light — extend(.uk-light), если dark — extend(.uk-dark)
.uk-card-primary.uk-card-body:extend(.uk-light all) when (@card-primary-color-mode = light) {}
.uk-card-primary.uk-card-body:extend(.uk-dark all) when (@card-primary-color-mode = dark) {}
```

**Ключевой момент:** `when` guard вычисляется **в момент компиляции файла**, где он написан. Если файл компилируется без переопределений переменных — guard фиксирует дефолтное значение.

### Решение

Переопределения переменных должны быть определены **ДО** импорта вендорных LESS-файлов.

**Правильный порядок в `template.less`:**
```less
// 1. Темовые переменные (переопределяют дефолты)
@import '../themes/visparfum/less/variables.less';

// 2. Вендор (when guards видят переопределённые значения)
@import '../vendor/uikit/less/uikit.less';

// 3. Темовые стили (хуки, дополнительные правила)
@import '../themes/visparfum/less/visparfum.less';
```

**Пример:** если в `variables.less` определено `@card-primary-color-mode: dark;`, то вендорный `card.less` при компиляции увидит `dark` → `extend(.uk-dark all)` → тёмные цвета для вложенных элементов.

### Неправильно

```less
// НЕПРАВИЛЬНО: тема после вендора — when guard уже зафиксировал дефолт
@import '../vendor/uikit/less/uikit.less';
@import '../themes/visparfum/less/variables.less';  // too late!
```

---

## AssetHelper — логика подключения CSS

> **Удалён.** `AssetHelper` (J3, `addStyleSheet`) больше не существует.
> Актуальная загрузка CSS/JS — через WebAssetManager в
> `ConfigController::manageAssets()`; алгоритм DEV/PROD, `default.css`
> и версионирование описаны в `.doc/11-dev-prod-modes.md`. Разделы ниже
> приведены как историческая справка о старой схеме.

### Текущая реализация (историческая)

```php
protected function loadStylesheets()
{
    $cssPath = JPATH_THEMES . '/' . template . '/css/';
    $templateFound = false;

    // 1. Подключить ВСЕ .css из css/ (кроме template.css)
    foreach (new DirectoryIterator($cssPath) as $file) {
        if ($file === 'template.css') {
            $templateFound = true;
            continue;  // отложить на потом
        }
        $doc->addStyleSheet($file);
    }

    // 2. Подключить template.css последним
    if ($templateFound) {
        $doc->addStyleSheet('template.css');
    }
}
```

### Нужная логика (с учётом тем)

```php
protected function loadStylesheets()
{
    $themeManager = ThemeManager::getInstance();

    if ($themeManager->hasActiveTheme()) {
        // Тема активна — загружаем CSS из папки темы
        $cssPath = $themeManager->getThemeBasePath() . '/css/';
    } else {
        // Темы нет — загружаем из основной папки css/
        $cssPath = JPATH_THEMES . '/' . template . '/css/';
    }

    $templateFound = false;

    foreach (new DirectoryIterator($cssPath) as $file) {
        if ($file->isFile() && $file->getExtension() === 'css') {
            if ($file->getFilename() === 'template.css') {
                $templateFound = true;
                continue;
            }
            $doc->addStyleSheet($this->tplpath . '/' . relativePath($file));
        }
    }

    if ($templateFound) {
        // Если template.css — компиляция (полный вывод), грузим только его
        // Если template.css — агрегатор (@import), грузим всё
        $content = file_get_contents($cssPath . 'template.css');
        if (strpos($content, '@import') !== false) {
            // Агрегатор — остальные файлы уже загружены выше
        }
        // В любом случае подключаем template.css последним
        $doc->addStyleSheet($relativePath . '/template.css');
    }
}
```

---

## script.php — копирование для production

```php
// post-install/post-update
$themeManager = ThemeManager::getInstance();

if ($themeManager->hasActiveTheme()) {
    $themeCssPath = $themeManager->getThemeBasePath() . '/css/template.css';
    $mainCssPath = JPATH_THEMES . '/' . $template . '/css/theme-' . $themeName . '.css';

    if (file_exists($themeCssPath)) {
        copy($themeCssPath, $mainCssPath);
    }
}
```

---

## Частые ошибки

### 1. Color-mode не работает

**Причина:** `@card-primary-color-mode` переопределён в теме, но импорт идёт ПОСЛЕ вендора.

**Решение:** переопределения переменных должны быть ДО `@import '../vendor/uikit/less/uikit.less';`

### 2. Дублирование CSS

**Причина:** `uikit.css` загружается и из `css/`, и через `@import` в `template.css`.

**Решение:** убрать `uikit.css` из `css/` или исключить из `loadStylesheets()`. Вендор хранится в `vendor/uikit/css/`.

### 3. После обновления стили сломались

**Причина:** `css/` перезаписан, production-файл потерян.

**Решение:** `script.php` должен повторно скопировать файлы из темы в `css/` после обновления.

### 4. LESS компилируется с дефолтными значениями

**Причина:** точка входа (`template.less`) не переопределяет переменные до вендора.

**Решение:** проверить порядок импорта в `template.less`. Сначала переменные темы, потом вендор.

---

## Чеклист при разработке нового шаблона

- [ ] `vendor/uikit/css/uikit.css` — вендорный CSS в изолированной папке
- [ ] `css/template.css` — агрегатор с `@import` из вендора
- [ ] `less/template.less` — точка входа для LESS (переменные → вендор → тема)
- [ ] `loadStylesheets()` — поддержка тем (приоритет: тема → css/)
- [ ] `script.php` — копирование для production (theme → css/)
- [ ] Color-mode переменные определены в `variables.less` темы (до вендора)
- [ ] Обновление шаблона не перезаписывает `themes/{theme}/css/`
