<?php

namespace Drupal\currency_converter;

/**
 * Stores and retrieves exchange rates from the database.
 */
interface ExchangeRateRepositoryInterface {

  /**
   * Replaces the stored rate for each given currency.
   *
   * @param float[] $rates
   *   Rates keyed by ISO 4217 currency code.
   * @param string $baseCurrency
   *   The base currency the rates are relative to.
   * @param int $timestamp
   *   The Unix timestamp of the fetch.
   */
  public function saveRates(array $rates, string $baseCurrency, int $timestamp): void;

  /**
   * Returns the stored rate for a single currency.
   *
   * @param string $currencyCode
   *   The ISO 4217 currency code.
   *
   * @return string|null
   *   The rate as a decimal string, or NULL if no rate is stored.
   */
  public function getRate(string $currencyCode): ?string;

  /**
   * Returns all stored rates.
   *
   * @return array
   *   An array keyed by currency code, each value an array with 'rate',
   *   'base_currency_code' and 'changed' keys.
   */
  public function getAllRates(): array;

}
