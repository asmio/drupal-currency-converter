<?php

namespace Drupal\currency_converter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\currency_converter\RateUpdaterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A single-button form to force an immediate exchange rate refresh.
 */
class RefreshRatesForm extends FormBase {

  public function __construct(
    protected RateUpdaterInterface $rateUpdater,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('currency_converter.rate_updater'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'currency_converter_refresh_rates_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update rates now'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->rateUpdater->updateNow();
    $this->messenger()->addStatus($this->t('Currency exchange rates have been updated.'));
  }

}
