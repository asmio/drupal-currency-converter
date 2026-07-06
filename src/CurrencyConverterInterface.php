<?php

namespace Drupal\currency_converter;

/**
 * Converts monetary amounts between currencies using stored exchange rates.
 */
interface CurrencyConverterInterface {

  /**
   * Converts an amount from one currency to another.
   *
   * @param string|float|int $amount
   *   The decimal amount to convert, e.g. 123 or "123.45".
   * @param string $from
   *   The ISO 4217 code of the currency the amount is in.
   * @param string $to
   *   The ISO 4217 code of the currency to convert to.
   *
   * @return string
   *   The converted amount as a decimal string.
   *
   * @throws \Drupal\currency_converter\Exception\ExchangeRateNotAvailableException
   *   If no stored rate allows resolving this currency pair.
   */
  public function convert(string|float|int $amount, string $from, string $to): string;

}
