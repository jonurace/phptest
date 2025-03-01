<?php

declare(strict_types=1);

namespace Drupal\potd\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for Product of the Day settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['potd.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'potd_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('potd.settings');

    $form['block_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Title'),
      '#default_value' => $config->get('block_title'),
    ];

    $form['max_potd_products'] = [
      '#type' => 'number',
      '#title' => $this->t('Max Products of the Day'),
      '#default_value' => $config->get('max_potd_products'),
      '#min' => 1,
      '#max' => 10,
    ];

    $form['admin_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Admin Email'),
      '#default_value' => $config->get('admin_email'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('potd.settings')
      ->set('block_title', $form_state->getValue('block_title'))
      ->set('max_potd_products', $form_state->getValue('max_potd_products'))
      ->set('admin_email', $form_state->getValue('admin_email'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}