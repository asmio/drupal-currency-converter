<?php

namespace Drupal\currency_converter\Money;

use Drupal\currency_converter\BaseCurrencyProviderInterface;
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
    protected BaseCurrencyProviderInterface $baseCurrencyProvider,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function quote(Currency $baseCurrency, Currency $counterCurrency): CurrencyPair {
    $configuredBase = $this->baseCurrencyProvider->getCode();

    $baseRate = $this->resolveRate($baseCurrency, $configuredBase);
    $counterRate = $this->resolveRate($counterCurrency, $configuredBase);

    if ($baseRate === NULL || $counterRate === NULL || bccomp($baseRate, '0', self::SCALE) === 0) {
      throw UnresolvableCurrencyPairException::createFromCurrencies($baseCurrency, $counterCurrency);
    }

    $ratio = bcdiv($counterRate, $baseRate, self::SCALE);

    return new CurrencyPair($baseCurrency, $counterCurrency, $ratio);
  }

  /**
   * Resolves a currency's stored rate relative to the configured base.
   *
   * Returns NULL (triggering an unresolvable pair) if there is no stored
   * rate, or if the stored rate was fetched relative to a base currency
   * that no longer matches the one currently configured — e.g. right after
   * an admin changes the base currency but before the next fetch runs. Using
   * a stale row here would silently apply the wrong ratio.
   */
  protected function resolveRate(Currency $currency, string $configuredBase): ?string {
    if ($currency->getCode() === $configuredBase) {
      return '1';
    }

    $stored = $this->rateRepository->getRate($currency->getCode());
    if ($stored === NULL || $stored['base_currency_code'] !== $configuredBase) {
      return NULL;
    }

    return $stored['rate'];
  }

}
