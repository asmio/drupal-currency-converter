# Currency Converter

Drupal-модуль, который хранит курсы валют и предоставляет сервис для конвертации сумм между валютами.

Что делает модуль:

- Хранит захардкоженный справочник валют (код ISO 4217 → название/символ) — `Drupal\currency_converter\CurrencyList`.
- Раз в сутки забирает актуальные курсы с [freecurrencyapi.com](https://freecurrencyapi.com/) (`/v1/latest`) и сохраняет их в БД. Запрос к API реализован вручную (Guzzle через `http_client`), без стороннего SDK.
- Обновление курсов запускается через `hook_cron()`, но с собственным таймером поверх State API: реальный фетч происходит не при каждом запуске cron, а только если с прошлого обновления прошло 24 часа.
- Предоставляет сервис `currency_converter.converter` для конвертации сумм между валютами (`moneyphp/money` под капотом).
- Показывает страницу в админке со всеми сохранёнными курсами и кнопкой ручного обновления.
- Права доступа разделены: отдельно на настройки, отдельно на просмотр курсов.

## Требования

- Drupal ^10 или ^11.
- PHP >= 8.1.
- Модуль [Key](https://www.drupal.org/project/key) (`drupal/key`) — используется для хранения API-ключа freecurrencyapi.com.
- Пакет [`moneyphp/money`](https://github.com/moneyphp/money) (^4.0) — используется для конвертации.
- Аккаунт и API-ключ на [freecurrencyapi.com](https://freecurrencyapi.com/).

## Установка

1. Поставить зависимости через Composer (модуль лежит в корне репозитория как отдельный composer-пакет, зависимости описаны в `composer.json`):

   ```
   composer require moneyphp/money drupal/key
   ```

2. Включить модуль:

   ```
   drush en currency_converter
   ```

   (или через UI: **Extend** → отметить *Currency Converter* → **Install**.)

3. Создать Key-запись с API-ключом freecurrencyapi.com: **Configuration → System → Keys** (`/admin/config/system/keys`) → **Add key**, вставить значение ключа. Тип хранения (Configuration / Environment variable / File и т.д.) — на усмотрение конфигурации сайта, модуль от этого не зависит, ему важен только Key ID.

## Настройка

Форма настроек: **Configuration → Web services → Currency Converter → Settings** (`/admin/config/services/currency-converter/settings`). Доступ — право **"Administer Currency Converter"**.

Поля формы:

- **freecurrencyapi.com API key** — выбор Key-записи, созданной на предыдущем шаге (элемент `key_select`, обязательное поле).
- **Base currency** — валюта, относительно которой запрашиваются и хранятся все курсы. По умолчанию `USD` (значение из `config/install/currency_converter.settings.yml`). Список вариантов — из захардкоженного справочника валют модуля.

Оба значения хранятся в конфигурации `currency_converter.settings` (ключи `api_key`, `base_currency`).

## Обновление курсов

Курсы можно обновить двумя способами:

1. **Кнопка в админке.** На странице отчёта **Configuration → Web services → Currency Converter** (`/admin/config/services/currency-converter`, право **"View currency exchange rates"**) есть кнопка **"Update rates now"** — она вызывает `RateUpdater::updateNow()` немедленно, минуя суточный таймер. После выполнения показывается сообщение об успехе или ошибке (если запрос к API не удался — красным).

2. **Через cron.** `hook_cron()` модуля на каждый запуск вызывает `RateUpdater::updateIfDue()`, который проверяет State-значение `currency_converter.last_updated`: реальный запрос к API уходит только если с последнего успешного обновления прошло 86400 секунд (сутки). Обычный запуск:

   ```
   drush cron
   ```

   Если курсы уже обновлялись менее суток назад, `drush cron` ничего не запросит у API — это ожидаемое поведение.

   Оба пути (кнопка и cron) защищены блокировкой (`lock` service), так что параллельный клик по кнопке и одновременный cron-запуск не приведут к двум одновременным запросам к API.

## Использование сервиса конвертации в коде

```php
/** @var \Drupal\currency_converter\CurrencyConverterInterface $converter */
$converter = \Drupal::service('currency_converter.converter');

// Или через DI в конструкторе сервиса/контроллера:
// public function __construct(
//   protected \Drupal\currency_converter\CurrencyConverterInterface $converter,
// ) {}

$result = $converter->convert(123, 'USD', 'RUB'); // "123.00" -> строка с суммой в рублях
```

Сигнатура: `convert(string|int $amount, string $from, string $to): string`.

- `$amount` принимается как `string` или `int` (не `float`) — сумма считается через `moneyphp/money` с точной арифметикой, float намеренно не поддерживается, чтобы не терять точность.
- Возвращает строку с десятичной суммой в валюте `$to`.
- Бросает `Drupal\currency_converter\Exception\ExchangeRateNotAvailableException`, если для пары валют нет сохранённого курса (например, курсы ещё ни разу не загружались, либо база валюты была изменена в настройках и курсы под неё ещё не обновились).
- Бросает `\InvalidArgumentException`, если `$amount`/`$from`/`$to` некорректны (не ISO 4217 код валюты, не парсящаяся сумма).
