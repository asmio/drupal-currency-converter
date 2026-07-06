<?php

namespace Drupal\currency_converter;

/**
 * Resolves the currently configured base currency.
 */
interface BaseCurrencyProviderInterface {

  /**
   * Returns the ISO 4217 code of the configured base currency.
   */
  public function getCode(): string;

}
