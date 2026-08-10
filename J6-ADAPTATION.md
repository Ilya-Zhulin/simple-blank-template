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

- [ ] Перевод подключения ассетов на **WebAssetManager**:
  - UIKit (css/js: `uikit.min.js`, `uikit-icons.min.js`, `uikit-custom-icons.min.js`, css темы с версионированием)
  - `js/theme.js`, `js/lazysizes.js`, quicklink
  - favicon/мета-данные — через регистрацию ассетов
- [ ] `index.php` — актуализировать рендер под J6 (head/footer через тему, позиции, откат от старых практик)
- [ ] Админ-поля конфигуратора на J6-API: `ThemeselectField`, `ThemenameField`, `PositionnavField`, `SectionnavField`, `LesscompilerField`, `ScripterField`
- [ ] `script.php` — install/uninstall/update под J6
- [ ] `templateDetails.xml` — поля/медиа/версии под J6, решение по `less_compile_button` (LESS в браузере устарел)
- [ ] Проверить `raw.php`, `error.php` (Throwable-объект), `offline.php` (2FA/webauthn в J6)
- [ ] `includes/Helper/AssetHelper.php` — убрать `setHeadData`-манипуляции под новые webassets

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