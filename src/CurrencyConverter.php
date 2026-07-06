<?php

namespace Drupal\currency_converter;

use Drupal\currency_converter\Exception\ExchangeRateNotAvailableException;
use Drupal\currency_converter\Money\RateExchange;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception as MoneyException;
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
    BaseCurrencyProviderInterface $baseCurrencyProvider,
  ) {
    $this->currencies = new ISOCurrencies();
    $this->converter = new Converter($this->currencies, new RateExchange($rateRepository, $baseCurrencyProvider));
    $this->parser = new DecimalMoneyParser($this->currencies);
    $this->formatter = new DecimalMoneyFormatter($this->currencies);
  }

  /**
   * {@inheritdoc}
   */
  public function convert(string|int $amount, string $from, string $to): string {
    try {
      $money = $this->parser->parse((string) $amount, new Currency($from));
      $converted = $this->converter->convert($money, new Currency($to));
    }
    catch (UnresolvableCurrencyPairException $e) {
      throw new ExchangeRateNotAvailableException(
        sprintf('No exchange rate available to convert %s to %s.', $from, $to),
        0,
        $e
      );
    }
    catch (MoneyException $e) {
      // Covers e.g. UnknownCurrencyException (bad $from/$to code) and
      // ParserException (bad $amount format) — invalid input, not a missing
      // rate, so it gets a different exception type than the case above.
      throw new \InvalidArgumentException(
        sprintf('Cannot convert "%s" from %s to %s: %s', $amount, $from, $to, $e->getMessage()),
        0,
        $e
      );
    }

    return $this->formatter->format($converted);
  }

}
