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
- [ ] CSS-ассеты: перенести сканирование css тем + версионирование `?v=filemtime` из `manageAssets()` в webasset-манифест (`version` / `useStyle`), googlefont уже на `https://`
- [ ] `index.php` — актуализировать рендер под J6 (head/footer через тему, позиции)
- [x] `script.php` — InstallerScriptInterface, `minimumJoomla = 6.0`, `minimumPhp = 8.3` (J6 требует PHP ^8.3), строки в en-GB/ru-RU (`TPL_SIMPLE_BLANK_INSTALL_*`)
- [x] **Медиа по стандарту J6**: `js/ css/ fonts/ less/ images/ vendor/` перенесены в `media/` (установка в `/media/templates/site/simple_blank`), `<media destination="site/templates/simple_blank">`; пути обновлены в `ConfigController` (`mediaUrl`, prod/cssPath), `ThemeManager::productionCopy`, `error.php`, `error404.php`, `offline.php`, `head.tmp`, favicon-ссылки, `joomla.asset.json`, less-компилятор (`jscript.js`/`jscript.min.js`), `ScripterField` (два базовых пути: media для less, templates для head/footer)
- [ ] Компиляцию LESS (запрос `/media/less/...` через com_templates `task=template.less`) проверить на живой J6 (поддержка media-файлов в редакторе шаблонов)
- [ ] Админ-поля конфигуратора на J6-API: `ThemeselectField`, `ThemenameField`, `PositionnavField`, `SectionnavField`, `LesscompilerField`, `ScripterField`
- [ ] `templateDetails.xml` — версия extension `6.0` (media destination уже в J6-формате)
- [ ] Проверить `raw.php`, `error.php` (Throwable-объект), `offline.php` (2FA/webauthn в J6)
- [ ] `AssetHelper.php` + `TemplateHelper.php` — дубликаты без вызовов в репо; удалить на этапе релиза (могут вызываться из `includes/head.php`/`footer.php` на живых сайтах)
- [ ] Удалён мёртвый `killbootstrap` (jui/bootstrap в J6 нет)
- [ ] `lazysizes.js` отсутствует в `js/` — файл доложить или убрать параметр

## Этап 2. Переделка оверрайдов (после базиса)

- [ ] `html/layouts/com_users/joomla/form/renderfield.php` — showon через `$wa->useScript('showon')`, inline-help (description)
- [ ] `html/layouts/com_users/joomla/form/field/password.php` — `field.passwordview`/`field.passwordstrength` (вместо vanilla-lock), data-min-* правила, Text::script
- [ ] `html/layouts/com_users/joomla/form/field/text.php` — charcounter (`short-and-sweet`), addonBefore/After, aria-describedby
- [ ] `html/layouts/joomla/system/message.php` — решить: UIkit-вариант или `webcomponent.joomla-alert` + `messages.js` (+noscript-fallback)
- [ ] `html/com_users/login/*` — keepalive/formvalidator в J6 (`system.keepalive` options), форма под новые поля
- [ ] `offline.php` — двухфакторная аутентификация J6 (webauthn)

## Этап 3. Проверка

- [ ] Сборка пакета, установка на чистую Joomla 6
- [ ] Проверка фронта (темы, позиции, формы, сообщения) и админки (конфигуратор, создание темы)
- [ ] Сверка с установкой на проде (языки, кодировка, производительность)