<?php

namespace Drupal\currency_converter;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\StateInterface;
use Drupal\currency_converter\Api\ExchangeRateProviderInterface;
use Drupal\currency_converter\Exception\ApiRequestException;
use Psr\Log\LoggerInterface;

/**
 * Fetches and stores exchange rates on its own daily timer.
 */
class RateUpdater implements RateUpdaterInterface {

  /**
   * Minimum number of seconds between two rate fetches.
   */
  protected const UPDATE_INTERVAL = 86400;

  /**
   * State key holding the timestamp of the last successful fetch.
   */
  protected const STATE_KEY = 'currency_converter.last_updated';

  public function __construct(
    protected ExchangeRateProviderInterface $apiClient,
    protected ExchangeRateRepositoryInterface $rateRepository,
    protected ConfigFactoryInterface $configFactory,
    protected StateInterface $state,
    protected TimeInterface $time,
    protected LoggerInterface $logger,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function updateIfDue(): void {
    $lastUpdated = (int) $this->state->get(self::STATE_KEY, 0);
    if ($this->time->getRequestTime() - $lastUpdated >= self::UPDATE_INTERVAL) {
      $this->updateNow();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function updateNow(): void {
    $baseCurrency = $this->configFactory->get('currency_converter.settings')->get('base_currency') ?: 'USD';

    try {
      $rates = $this->apiClient->getLatestRates($baseCurrency);
    }
    catch (ApiRequestException $e) {
      // Leave the state timestamp untouched so the next cron run retries.
      $this->logger->error('Currency exchange rate update failed: @message', ['@message' => $e->getMessage()]);
      return;
    }

    $timestamp = $this->time->getRequestTime();
    $this->rateRepository->saveRates($rates, $baseCurrency, $timestamp);
    $this->state->set(self::STATE_KEY, $timestamp);
    $this->logger->info('Updated @count currency exchange rates relative to @base.', [
      '@count' => count($rates),
      '@base' => $baseCurrency,
    ]);
  }

}
