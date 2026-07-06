<?php

namespace Drupal\currency_converter\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\currency_converter\CurrencyListInterface;
use Drupal\currency_converter\ExchangeRateRepositoryInterface;
use Drupal\currency_converter\Form\RefreshRatesForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Displays the stored currency exchange rates.
 */
class RatesOverviewController extends ControllerBase {

  public function __construct(
    protected ExchangeRateRepositoryInterface $rateRepository,
    protected CurrencyListInterface $currencyList,
    protected ConfigFactoryInterface $configFactory,
    protected FormBuilderInterface $formBuilder,
    protected DateFormatterInterface $dateFormatter,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('currency_converter.rate_repository'),
      $container->get('currency_converter.currency_list'),
      $container->get('config.factory'),
      $container->get('form_builder'),
      $container->get('date.formatter'),
    );
  }

  /**
   * Builds the rates overview page.
   */
  public function build(): array {
    $baseCurrency = $this->configFactory->get('currency_converter.settings')->get('base_currency') ?: 'USD';

    $build['refresh_form'] = $this->formBuilder->getForm(RefreshRatesForm::class);

    $rates = $this->rateRepository->getAllRates();
    if (empty($rates)) {
      $build['empty'] = [
        '#markup' => $this->t('No exchange rates have been fetched yet. Cron will fetch them automatically, or use the button above.'),
      ];
      return $build;
    }

    $rows = [];
    foreach ($rates as $code => $info) {
      $rows[] = [
        $code,
        $this->currencyList->getLabel($code) ?? $code,
        $info['rate'],
        $info['base_currency_code'],
        $this->dateFormatter->format($info['changed'], 'short'),
      ];
    }

    $build['table'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Code'),
        $this->t('Currency'),
        $this->t('Rate (relative to @base)', ['@base' => $baseCurrency]),
        $this->t('Base currency'),
        $this->t('Last updated'),
      ],
      '#rows' => $rows,
    ];

    return $build;
  }

}
