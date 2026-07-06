<?php

namespace Drupal\currency_converter\Api;

use Drupal\currency_converter\Exception\ApiRequestException;

/**
 * Provides latest exchange rates from an external source.
 */
interface ExchangeRateProviderInterface {

  /**
   * Fetches the latest exchange rates relative to a base currency.
   *
   * @param string $baseCurrency
   *   The ISO 4217 code all returned rates should be relative to.
   *
   * @return string[]
   *   An array of fixed-point decimal rate strings keyed by ISO 4217
   *   currency code.
   *
   * @throws \Drupal\currency_converter\Exception\ApiRequestException
   *   If the request fails or the response is malformed.
   */
  public function getLatestRates(string $baseCurrency): array;

}
