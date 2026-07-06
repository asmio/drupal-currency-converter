<?php

namespace Drupal\currency_converter\Money;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\currency_converter\ExchangeRateRepositoryInterface;
use Money\Currency;
use Money\CurrencyPair;
use Money\Exception\UnresolvableCurrencyPairException;
use Money\Exchange;

/**
 * A moneyphp Exchange backed by the rates stored in the database.
 */
class RateExchange implements Exchange {

  /**
   * Precision used for the bcdiv() cross-rate calculation.
   */
  protected const SCALE = 10;

  public function __construct(
    protected ExchangeRateRepositoryInterface $rateRepository,
    protected ConfigFactoryInterface $configFactory,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function quote(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair {
    $configuredBase = $this->configFactory->get('currency_converter.settings')->get('base_currency') ?: 'USD';

    $baseRate = $this->resolveRate($baseCurrency, $configuredBase);
    $counterRate = $this->resolveRate($counterCurrency, $configuredBase);

    if ($baseRate === NULL || $counterRate === NULL) {
      throw UnresolvableCurrencyPairException::createFromCurrencies($baseCurrency, $counterCurrency);
    }

    $ratio = bcdiv($counterRate, $baseRate, self::SCALE);

    return new CurrencyPair($baseCurrency, $counterCurrency, $ratio);
  }

  /**
   * Resolves a currency's stored rate relative to the configured base.
   */
  protected function resolveRate(Currency $currency, string $configuredBase): ?string {
    if ($currency->getCode() === $configuredBase) {
      return '1';
    }
    return $this->rateRepository->getRate($currency->getCode());
  }

}
