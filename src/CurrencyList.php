<?php

namespace Drupal\currency_converter;

/**
 * Hardcoded reference list of currencies (code, label, symbol).
 *
 * freecurrencyapi.com's /v1/latest response only contains currency codes and
 * rates, no human-readable metadata, so this module supplies its own. The
 * set below covers active ISO 4217 currencies; any code the API returns that
 * is missing here still gets stored, it just falls back to displaying its
 * bare code instead of a label.
 */
class CurrencyList implements CurrencyListInterface {

  /**
   * Currency metadata keyed by ISO 4217 code.
   */
  protected const CURRENCIES = [
    'AED' => ['label' => 'UAE Dirham', 'symbol' => 'AED'],
    'AFN' => ['label' => 'Afghani', 'symbol' => 'Af'],
    'ALL' => ['label' => 'Lek', 'symbol' => 'L'],
    'AMD' => ['label' => 'Armenian Dram', 'symbol' => 'AMD'],
    'ANG' => ['label' => 'Netherlands Antillean Guilder', 'symbol' => 'ƒ'],
    'AOA' => ['label' => 'Kwanza', 'symbol' => 'Kz'],
    'ARS' => ['label' => 'Argentine Peso', 'symbol' => '$'],
    'AUD' => ['label' => 'Australian Dollar', 'symbol' => '$'],
    'AWG' => ['label' => 'Aruban Florin', 'symbol' => 'ƒ'],
    'AZN' => ['label' => 'Azerbaijan Manat', 'symbol' => '₼'],
    'BAM' => ['label' => 'Convertible Mark', 'symbol' => 'KM'],
    'BBD' => ['label' => 'Barbados Dollar', 'symbol' => '$'],
    'BDT' => ['label' => 'Taka', 'symbol' => '৳'],
    'BGN' => ['label' => 'Bulgarian Lev', 'symbol' => 'лв'],
    'BHD' => ['label' => 'Bahraini Dinar', 'symbol' => 'BHD'],
    'BIF' => ['label' => 'Burundi Franc', 'symbol' => 'FBu'],
    'BMD' => ['label' => 'Bermudian Dollar', 'symbol' => '$'],
    'BND' => ['label' => 'Brunei Dollar', 'symbol' => '$'],
    'BOB' => ['label' => 'Boliviano', 'symbol' => 'Bs'],
    'BRL' => ['label' => 'Brazilian Real', 'symbol' => 'R$'],
    'BSD' => ['label' => 'Bahamian Dollar', 'symbol' => '$'],
    'BTN' => ['label' => 'Ngultrum', 'symbol' => 'Nu.'],
    'BWP' => ['label' => 'Pula', 'symbol' => 'P'],
    'BYN' => ['label' => 'Belarusian Ruble', 'symbol' => 'Br'],
    'BZD' => ['label' => 'Belize Dollar', 'symbol' => '$'],
    'CAD' => ['label' => 'Canadian Dollar', 'symbol' => '$'],
    'CDF' => ['label' => 'Congolese Franc', 'symbol' => 'FC'],
    'CHF' => ['label' => 'Swiss Franc', 'symbol' => 'CHF'],
    'CLP' => ['label' => 'Chilean Peso', 'symbol' => '$'],
    'CNY' => ['label' => 'Yuan Renminbi', 'symbol' => '¥'],
    'COP' => ['label' => 'Colombian Peso', 'symbol' => '$'],
    'CRC' => ['label' => 'Costa Rican Colon', 'symbol' => '₡'],
    'CUP' => ['label' => 'Cuban Peso', 'symbol' => '$'],
    'CVE' => ['label' => 'Cabo Verde Escudo', 'symbol' => '$'],
    'CZK' => ['label' => 'Czech Koruna', 'symbol' => 'Kč'],
    'DJF' => ['label' => 'Djibouti Franc', 'symbol' => 'Fdj'],
    'DKK' => ['label' => 'Danish Krone', 'symbol' => 'kr'],
    'DOP' => ['label' => 'Dominican Peso', 'symbol' => '$'],
    'DZD' => ['label' => 'Algerian Dinar', 'symbol' => 'DZD'],
    'EGP' => ['label' => 'Egyptian Pound', 'symbol' => 'E£'],
    'ERN' => ['label' => 'Nakfa', 'symbol' => 'Nfk'],
    'ETB' => ['label' => 'Ethiopian Birr', 'symbol' => 'Br'],
    'EUR' => ['label' => 'Euro', 'symbol' => '€'],
    'FJD' => ['label' => 'Fiji Dollar', 'symbol' => '$'],
    'FKP' => ['label' => 'Falkland Islands Pound', 'symbol' => '£'],
    'GBP' => ['label' => 'Pound Sterling', 'symbol' => '£'],
    'GEL' => ['label' => 'Lari', 'symbol' => '₾'],
    'GHS' => ['label' => 'Ghana Cedi', 'symbol' => 'GH₵'],
    'GIP' => ['label' => 'Gibraltar Pound', 'symbol' => '£'],
    'GMD' => ['label' => 'Dalasi', 'symbol' => 'D'],
    'GNF' => ['label' => 'Guinean Franc', 'symbol' => 'FG'],
    'GTQ' => ['label' => 'Quetzal', 'symbol' => 'Q'],
    'GYD' => ['label' => 'Guyana Dollar', 'symbol' => '$'],
    'HKD' => ['label' => 'Hong Kong Dollar', 'symbol' => '$'],
    'HNL' => ['label' => 'Lempira', 'symbol' => 'L'],
    'HRK' => ['label' => 'Kuna', 'symbol' => 'kn'],
    'HTG' => ['label' => 'Gourde', 'symbol' => 'G'],
    'HUF' => ['label' => 'Forint', 'symbol' => 'Ft'],
    'IDR' => ['label' => 'Rupiah', 'symbol' => 'Rp'],
    'ILS' => ['label' => 'New Israeli Sheqel', 'symbol' => '₪'],
    'INR' => ['label' => 'Indian Rupee', 'symbol' => '₹'],
    'IQD' => ['label' => 'Iraqi Dinar', 'symbol' => 'IQD'],
    'IRR' => ['label' => 'Iranian Rial', 'symbol' => '﷼'],
    'ISK' => ['label' => 'Iceland Krona', 'symbol' => 'kr'],
    'JMD' => ['label' => 'Jamaican Dollar', 'symbol' => '$'],
    'JOD' => ['label' => 'Jordanian Dinar', 'symbol' => 'JOD'],
    'JPY' => ['label' => 'Yen', 'symbol' => '¥'],
    'KES' => ['label' => 'Kenyan Shilling', 'symbol' => 'KSh'],
    'KGS' => ['label' => 'Som', 'symbol' => 'с'],
    'KHR' => ['label' => 'Riel', 'symbol' => '៛'],
    'KMF' => ['label' => 'Comorian Franc', 'symbol' => 'CF'],
    'KRW' => ['label' => 'Won', 'symbol' => '₩'],
    'KWD' => ['label' => 'Kuwaiti Dinar', 'symbol' => 'KWD'],
    'KYD' => ['label' => 'Cayman Islands Dollar', 'symbol' => '$'],
    'KZT' => ['label' => 'Tenge', 'symbol' => '₸'],
    'LAK' => ['label' => 'Lao Kip', 'symbol' => '₭'],
    'LBP' => ['label' => 'Lebanese Pound', 'symbol' => 'L£'],
    'LKR' => ['label' => 'Sri Lanka Rupee', 'symbol' => 'Rs'],
    'LRD' => ['label' => 'Liberian Dollar', 'symbol' => '$'],
    'LSL' => ['label' => 'Loti', 'symbol' => 'L'],
    'LYD' => ['label' => 'Libyan Dinar', 'symbol' => 'LYD'],
    'MAD' => ['label' => 'Moroccan Dirham', 'symbol' => 'MAD'],
    'MDL' => ['label' => 'Moldovan Leu', 'symbol' => 'L'],
    'MGA' => ['label' => 'Malagasy Ariary', 'symbol' => 'Ar'],
    'MKD' => ['label' => 'Denar', 'symbol' => 'ден'],
    'MMK' => ['label' => 'Kyat', 'symbol' => 'K'],
    'MNT' => ['label' => 'Tugrik', 'symbol' => '₮'],
    'MOP' => ['label' => 'Pataca', 'symbol' => 'MOP$'],
    'MRU' => ['label' => 'Ouguiya', 'symbol' => 'UM'],
    'MUR' => ['label' => 'Mauritius Rupee', 'symbol' => 'Rs'],
    'MVR' => ['label' => 'Rufiyaa', 'symbol' => 'Rf'],
    'MWK' => ['label' => 'Malawi Kwacha', 'symbol' => 'MK'],
    'MXN' => ['label' => 'Mexican Peso', 'symbol' => '$'],
    'MYR' => ['label' => 'Malaysian Ringgit', 'symbol' => 'RM'],
    'MZN' => ['label' => 'Mozambique Metical', 'symbol' => 'MT'],
    'NAD' => ['label' => 'Namibia Dollar', 'symbol' => '$'],
    'NGN' => ['label' => 'Naira', 'symbol' => '₦'],
    'NIO' => ['label' => 'Cordoba Oro', 'symbol' => 'C$'],
    'NOK' => ['label' => 'Norwegian Krone', 'symbol' => 'kr'],
    'NPR' => ['label' => 'Nepalese Rupee', 'symbol' => 'Rs'],
    'NZD' => ['label' => 'New Zealand Dollar', 'symbol' => '$'],
    'OMR' => ['label' => 'Rial Omani', 'symbol' => 'OMR'],
    'PAB' => ['label' => 'Balboa', 'symbol' => 'B/.'],
    'PEN' => ['label' => 'Sol', 'symbol' => 'S/'],
    'PGK' => ['label' => 'Kina', 'symbol' => 'K'],
    'PHP' => ['label' => 'Philippine Peso', 'symbol' => '₱'],
    'PKR' => ['label' => 'Pakistan Rupee', 'symbol' => 'Rs'],
    'PLN' => ['label' => 'Zloty', 'symbol' => 'zł'],
    'PYG' => ['label' => 'Guarani', 'symbol' => '₲'],
    'QAR' => ['label' => 'Qatari Rial', 'symbol' => 'QAR'],
    'RON' => ['label' => 'Romanian Leu', 'symbol' => 'lei'],
    'RSD' => ['label' => 'Serbian Dinar', 'symbol' => 'дин.'],
    'RUB' => ['label' => 'Russian Ruble', 'symbol' => '₽'],
    'RWF' => ['label' => 'Rwanda Franc', 'symbol' => 'FRw'],
    'SAR' => ['label' => 'Saudi Riyal', 'symbol' => 'SAR'],
    'SBD' => ['label' => 'Solomon Islands Dollar', 'symbol' => '$'],
    'SCR' => ['label' => 'Seychelles Rupee', 'symbol' => 'Rs'],
    'SDG' => ['label' => 'Sudanese Pound', 'symbol' => 'SDG'],
    'SEK' => ['label' => 'Swedish Krona', 'symbol' => 'kr'],
    'SGD' => ['label' => 'Singapore Dollar', 'symbol' => '$'],
    'SHP' => ['label' => 'Saint Helena Pound', 'symbol' => '£'],
    'SLE' => ['label' => 'Leone', 'symbol' => 'Le'],
    'SOS' => ['label' => 'Somali Shilling', 'symbol' => 'Sh'],
    'SRD' => ['label' => 'Surinam Dollar', 'symbol' => '$'],
    'SSP' => ['label' => 'South Sudanese Pound', 'symbol' => 'SSP'],
    'STN' => ['label' => 'Dobra', 'symbol' => 'Db'],
    'SYP' => ['label' => 'Syrian Pound', 'symbol' => 'SYP'],
    'SZL' => ['label' => 'Lilangeni', 'symbol' => 'L'],
    'THB' => ['label' => 'Baht', 'symbol' => '฿'],
    'TJS' => ['label' => 'Somoni', 'symbol' => 'SM'],
    'TMT' => ['label' => 'Turkmenistan New Manat', 'symbol' => 'm'],
    'TND' => ['label' => 'Tunisian Dinar', 'symbol' => 'TND'],
    'TOP' => ['label' => 'Pa\'anga', 'symbol' => 'T$'],
    'TRY' => ['label' => 'Turkish Lira', 'symbol' => '₺'],
    'TTD' => ['label' => 'Trinidad and Tobago Dollar', 'symbol' => '$'],
    'TWD' => ['label' => 'New Taiwan Dollar', 'symbol' => 'NT$'],
    'TZS' => ['label' => 'Tanzanian Shilling', 'symbol' => 'TSh'],
    'UAH' => ['label' => 'Hryvnia', 'symbol' => '₴'],
    'UGX' => ['label' => 'Uganda Shilling', 'symbol' => 'USh'],
    'USD' => ['label' => 'US Dollar', 'symbol' => '$'],
    'UYU' => ['label' => 'Peso Uruguayo', 'symbol' => '$'],
    'UZS' => ['label' => 'Uzbekistan Sum', 'symbol' => 'so\'m'],
    'VES' => ['label' => 'Bolivar Soberano', 'symbol' => 'Bs.'],
    'VND' => ['label' => 'Dong', 'symbol' => '₫'],
    'VUV' => ['label' => 'Vatu', 'symbol' => 'VT'],
    'WST' => ['label' => 'Tala', 'symbol' => 'WS$'],
    'XAF' => ['label' => 'CFA Franc BEAC', 'symbol' => 'FCFA'],
    'XCD' => ['label' => 'East Caribbean Dollar', 'symbol' => '$'],
    'XOF' => ['label' => 'CFA Franc BCEAO', 'symbol' => 'CFA'],
    'XPF' => ['label' => 'CFP Franc', 'symbol' => '₣'],
    'YER' => ['label' => 'Yemeni Rial', 'symbol' => 'YER'],
    'ZAR' => ['label' => 'Rand', 'symbol' => 'R'],
    'ZMW' => ['label' => 'Zambian Kwacha', 'symbol' => 'ZK'],
    'ZWL' => ['label' => 'Zimbabwe Dollar', 'symbol' => 'Z$'],
  ];

  /**
   * {@inheritdoc}
   */
  public function getAll(): array {
    return self::CURRENCIES;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel(string $code): ?string {
    return self::CURRENCIES[$code]['label'] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {
    $options = [];
    foreach (self::CURRENCIES as $code => $info) {
      $options[$code] = sprintf('%s - %s', $code, $info['label']);
    }
    return $options;
  }

}
