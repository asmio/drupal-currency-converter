<?php

namespace Drupal\currency_converter;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\currency_converter\Exception\ExchangeRateNotAvailableException;
use Drupal\currency_converter\Money\RateExchange;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\UnresolvableCurrencyPairException;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;

/**
 * Converts monetary amounts using moneyphp/money and stored exchange rates.
 */
class CurrencyConverter implements CurrencyConverterInterface {

  protected ISOCurrencies $currencies;

  protected Converter $converter;

  protected DecimalMoneyParser $parser;

  protected DecimalMoneyFormatter $formatter;

  public function __construct(
    ExchangeRateRepositoryInterface $rateRepository,
    ConfigFactoryInterface $configFactory,
  ) {
    $this->currencies = new ISOCurrencies();
    $this->converter = new Converter($this->currencies, new RateExchange($rateRepository, $configFactory));
    $this->parser = new DecimalMoneyParser($this->currencies);
    $this->formatter = new DecimalMoneyFormatter($this->currencies);
  }

  /**
   * {@inheritdoc}
   */
  public function convert(string|float|int $amount, string $from, string $to): string {
    $money = $this->parser->parse((string) $amount, new Currency($from));

    try {
      $converted = $this->converter->convert($money, new Currency($to));
    }
    catch (UnresolvableCurrencyPairException $e) {
      throw new ExchangeRateNotAvailableException(
        sprintf('No exchange rate available to convert %s to %s.', $from, $to),
        0,
        $e
      );
    }

    return $this->formatter->format($converted);
  }

}
