<?php

namespace Drupal\currency_converter;

/**
 * Converts monetary amounts between currencies using stored exchange rates.
 */
interface CurrencyConverterInterface {

  /**
   * Converts an amount from one currency to another.
   *
   * Amounts are deliberately typed as string|int rather than float: floats
   * lose exact decimal precision, which defeats the point of using
   * moneyphp/money's arbitrary-precision math. Callers should pass a plain
   * decimal string (e.g. "123.45") or an integer.
   *
   * @param string|int $amount
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
   * @throws \InvalidArgumentException
   *   If $amount is not a valid decimal amount, or $from/$to is not a known
   *   ISO 4217 currency code.
   */
  public function convert(string|int $amount, string $from, string $to): string;

}
