<?php

namespace Drupal\currency_converter;

/**
 * Provides the hardcoded list of known currencies.
 */
interface CurrencyListInterface {

  /**
   * Returns all known currencies.
   *
   * @return array
   *   An array keyed by ISO 4217 currency code, each value an array with
   *   'label' and 'symbol' keys.
   */
  public function getAll(): array;

  /**
   * Returns the human-readable label for a currency code, if known.
   *
   * @param string $code
   *   The ISO 4217 currency code.
   *
   * @return string|null
   *   The label, or NULL if the code is not in the hardcoded list.
   */
  public function getLabel(string $code): ?string;

  /**
   * Returns select options suitable for a form 'select' element.
   *
   * @return string[]
   *   An array of "CODE - Label" strings keyed by currency code.
   */
  public function getOptions(): array;

}
