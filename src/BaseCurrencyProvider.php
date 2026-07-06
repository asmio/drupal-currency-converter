<?php

namespace Drupal\currency_converter;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Reads the base currency from configuration, defaulting to USD.
 */
class BaseCurrencyProvider implements BaseCurrencyProviderInterface {

  protected const DEFAULT_CODE = 'USD';

  public function __construct(
    protected ConfigFactoryInterface $configFactory,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getCode(): string {
    return $this->configFactory->get('currency_converter.settings')->get('base_currency') ?: self::DEFAULT_CODE;
  }

}
