<?php

namespace Drupal\currency_converter\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\currency_converter\CurrencyListInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configures the freecurrencyapi.com API key and base currency.
 */
class SettingsForm extends ConfigFormBase {

  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typed_config_manager,
    protected CurrencyListInterface $currencyList,
  ) {
    parent::__construct($config_factory, $typed_config_manager);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('currency_converter.currency_list'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'currency_converter_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['currency_converter.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('currency_converter.settings');

    $form['api_key'] = [
      '#type' => 'key_select',
      '#title' => $this->t('freecurrencyapi.com API key'),
      '#description' => $this->t('The Key entity holding your freecurrencyapi.com API key.'),
      '#default_value' => $config->get('api_key'),
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
    ];

    $form['base_currency'] = [
      '#type' => 'select',
      '#title' => $this->t('Base currency'),
      '#description' => $this->t('All exchange rates are fetched and stored relative to this currency.'),
      '#options' => $this->currencyList->getOptions(),
      '#default_value' => $config->get('base_currency'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('currency_converter.settings')
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('base_currency', $form_state->getValue('base_currency'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
