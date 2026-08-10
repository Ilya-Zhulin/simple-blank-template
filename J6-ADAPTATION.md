# Адаптация шаблона под Joomla 6 (план работ)

> Внутренний план разработки. Ветка: `cleanup-j6` (после слияния — `dev`).

## Выполнено (deprecated-чистка)

- [x] `error.php` — JError (удалён в J4+) → `\RuntimeException` + `Text::_`
- [x] `offline.php`, `html/com_users/login/*` — JFactory/JText/JRoute/JUri/JHtml/JPluginHelper/JComponentHelper → современные классы
- [x] `html/layouts/com_users/...` и `html/layouts/joomla/system/message.php` — замена legacy-классов, пометки `J6 REVIEW`
- [x] jQuery/MooTools удалены: `jquery.framework` из `AssetHelper`/`ConfigController`, `Form.PasswordStrength` (MooTools), `jui/cms.js`, `html5fallback.js`
- [x] `password.php` — lock-кнопка на vanilla JS (без jQuery)
- [x] `renderlabel.php` — упрощён по J6 API (в J6 tooltip/popover из label убраны)

## Этап 1. Архитектура (большое, перед оверрайдами)

- [x] JS-ассеты переведены на **WebAssetManager**: создан `joomla.asset.json` (uikit, uikit-icons, uikit-custom-icons, theme, lazysizes, quicklink), `ConfigController::manageAssets()` использует `$wa->useScript()`; quicklink реально подключается по параметру `qlenable`
- [x] CSS-ассеты: **решено оставить рантайм-сканирование** css тем в `manageAssets()` (динамические папки тем/производственный режим не поддаются статическому манифесту), `?v=filemtime` версионирование работает; googlefont на `https://`; `css_versioning` не затрагивается (кроме prod)
- [x] `index.php` — актуализирован (пустой `class=""` на html убран, `extract($config->data)` оставлен — рабочий вариант), head/footer — условные includes остаются
- [x] `raw.php`, `component.php`, `error.php` — проверены: legacy-классов нет (grep 0)
- [x] `script.php` — InstallerScriptInterface, `minimumJoomla = 6.0`, `minimumPhp = 8.3` (J6 требует PHP ^8.3), строки в en-GB/ru-RU (`TPL_SIMPLE_BLANK_INSTALL_*`)
- [x] **Медиа по стандарту J6**: `js/ css/ fonts/ less/ images/ vendor/` перенесены в `media/` (установка в `/media/templates/site/simple_blank`), `<media destination="site/templates/simple_blank">`; пути обновлены в `ConfigController` (`mediaUrl`, prod/cssPath), `ThemeManager::productionCopy`, `error.php`, `error404.php`, `offline.php`, `head.tmp`, favicon-ссылки, `joomla.asset.json`, less-компилятор (`jscript.js`/`jscript.min.js`), `ScripterField` (два базовых пути: media для less, templates для head/footer)
- [ ] Компиляцию LESS (запрос `/media/less/...` через com_templates `task=template.less`) проверить на живой J6 (поддержка media-файлов в редакторе шаблонов)
- [x] Админ-поля: `PositionnavField` переведён на WebAssetManager, `ScripterField` — `DatabaseInterface::class`; `jscript.php`/`grid.php` (J3-классы `JFormField`, не используются) удалены; `ThemeselectField`/`ThemenameField`/`LesscompilerField` — уже на J4/J6-API
- [x] `templateDetails.xml` — версия extension `6.0` (media destination уже в J6-формате)
- [x] Grep по всему репо: `JFactory/JText/JRoute/JUri/JHtml/jimport/JString/JError` — 0 совпадений
- [ ] Проверить `raw.php`, `error.php` (Throwable-объект), `offline.php` (2FA/webauthn в J6)
- [ ] `AssetHelper.php` + `TemplateHelper.php` — дубликаты без вызовов в репо; удалить на этапе релиза (могут вызываться из `includes/head.php`/`footer.php` на живых сайтах)
- [ ] Удалён мёртвый `killbootstrap` (jui/bootstrap в J6 нет)
- [ ] `lazysizes.js` отсутствует в `js/` — файл доложить или убрать параметр

## Этап 2. Переделка оверрайдов (после базиса)

- [x] `renderfield.php` — showon через `$wa->useScript('showon')` (+`showonEnabled` из поля), inline-help (`description`/`descClass`/`inlineHelp`), hiddenLabel
- [x] `password.php` — приведён к ядру J6: `field.passwordview` (глазок) + `field.passwordstrength` (`js-password-strength`, `meteredPassword`), `data-min-*`/`data-min-force`, `$rules`-требования, lock через `.input-password-modify.locked` + `Text::script` (J6-скрипт), aria-describedby; собственный inline-JS lock удалён
- [x] `text.php` — добавлен `$dataAttribute` (совместимость с J6 data-фичами)
- [x] `message.php` — оставлен UIkit-вариант (осознанный отказ от `webcomponent.joomla-alert`), маппинг типов по константам `CMSApplication` (J6), `aria-live="polite"`
- [x] `html/com_users/login/*` — `behavior.keepalive`/`behavior.formvalidator` → `$wa->useScript('keepalive')`/`useScript('form.validate')`
- [x] `offline.php` — приведён к ядру J6: 2FA-блок убран (в ядре J6 секрет-ключ/webauthn в offline нет; `UsersHelper` удалён), autocomplete/autocapitalize, button-submit, viewport

## Этап 3. Проверка

- [ ] Сборка пакета, установка на чистую Joomla 6
- [ ] Проверка фронта (темы, позиции, формы, сообщения) и админки (конфигуратор, создание темы)
- [ ] Сверка с установкой на проде (языки, кодировка, производительность)