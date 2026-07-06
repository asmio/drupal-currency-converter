<?php

namespace Drupal\currency_converter;

use Drupal\Core\Database\Connection;

/**
 * Database-backed storage for exchange rates.
 */
class ExchangeRateRepository implements ExchangeRateRepositoryInterface {

  protected const TABLE = 'currency_converter_rate';

  public function __construct(
    protected Connection $database,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function saveRates(array $rates, string $baseCurrency, int $timestamp): void {
    $transaction = $this->database->startTransaction();
    try {
      foreach ($rates as $currencyCode => $rate) {
        $this->database->merge(self::TABLE)
          ->keys(['currency_code' => $currencyCode])
          ->fields([
            'rate' => $rate,
            'base_currency_code' => $baseCurrency,
            'changed' => $timestamp,
          ])
          ->execute();
      }
    }
    catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getRate(string $currencyCode): ?array {
    $row = $this->database->select(self::TABLE, 'r')
      ->fields('r', ['rate', 'base_currency_code'])
      ->condition('currency_code', $currencyCode)
      ->execute()
      ->fetchAssoc();

    return $row ?: NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getAllRates(): array {
    $result = $this->database->select(self::TABLE, 'r')
      ->fields('r', ['currency_code', 'rate', 'base_currency_code', 'changed'])
      ->orderBy('currency_code')
      ->execute();

    $rates = [];
    foreach ($result as $row) {
      $rates[$row->currency_code] = [
        'rate' => $row->rate,
        'base_currency_code' => $row->base_currency_code,
        'changed' => (int) $row->changed,
      ];
    }
    return $rates;
  }

}
