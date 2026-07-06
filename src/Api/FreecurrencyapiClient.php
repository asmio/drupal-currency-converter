<?php

namespace Drupal\currency_converter\Api;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\currency_converter\Exception\ApiRequestException;
use Drupal\key\KeyRepositoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Fetches exchange rates from the freecurrencyapi.com "latest" endpoint.
 *
 * This hand-rolls the HTTP request and JSON parsing rather than depending on
 * a vendor SDK for the API.
 */
class FreecurrencyapiClient implements ExchangeRateProviderInterface {

  /**
   * The freecurrencyapi.com "latest" rates endpoint.
   */
  protected const ENDPOINT = 'https://api.freecurrencyapi.com/v1/latest';

  public function __construct(
    protected ClientInterface $httpClient,
    protected ConfigFactoryInterface $configFactory,
    protected KeyRepositoryInterface $keyRepository,
    protected LoggerInterface $logger,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getLatestRates(string $baseCurrency): array {
    $apiKey = $this->getApiKey();

    try {
      $response = $this->httpClient->request('GET', self::ENDPOINT, [
        'query' => [
          'apikey' => $apiKey,
          'base_currency' => $baseCurrency,
        ],
      ]);
    }
    catch (GuzzleException $e) {
      $this->logger->error('Request to freecurrencyapi.com failed: @message', ['@message' => $e->getMessage()]);
      throw new ApiRequestException('Request to freecurrencyapi.com failed.', 0, $e);
    }

    $payload = json_decode((string) $response->getBody(), TRUE);
    if (!is_array($payload) || !isset($payload['data']) || !is_array($payload['data'])) {
      $this->logger->error('Unexpected response from freecurrencyapi.com: @body', ['@body' => (string) $response->getBody()]);
      throw new ApiRequestException('Unexpected response format from freecurrencyapi.com.');
    }

    return array_map(static fn ($rate) => (float) $rate, $payload['data']);
  }

  /**
   * Resolves the configured API key's actual secret value.
   *
   * @throws \Drupal\currency_converter\Exception\ApiRequestException
   *   If no key is configured or the key cannot be found.
   */
  protected function getApiKey(): string {
    $keyId = $this->configFactory->get('currency_converter.settings')->get('api_key');
    if (empty($keyId)) {
      throw new ApiRequestException('No freecurrencyapi.com API key is configured.');
    }

    $key = $this->keyRepository->getKey($keyId);
    $apiKey = $key?->getKeyValue();
    if (empty($apiKey)) {
      throw new ApiRequestException('The configured freecurrencyapi.com API key could not be loaded.');
    }

    return $apiKey;
  }

}
