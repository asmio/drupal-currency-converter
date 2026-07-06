<?php

namespace Drupal\currency_converter\Exception;

/**
 * Thrown when a conversion is requested for a currency with no stored rate.
 */
class ExchangeRateNotAvailableException extends \RuntimeException {

}
