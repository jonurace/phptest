<?php

declare(strict_types=1);

namespace Drupal\potd\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Form for managing products.
 */
class ProductForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'potd_product_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $product_id = NULL): array {
    $database = \Drupal::database();
    $product = [];

    if ($product_id) {
      $product = $database->select('potd_products', 'p')
        ->fields('p')
        ->condition('id', $product_id)
        ->execute()
        ->fetchAssoc();
    }

    $form['product_id'] = [
      '#type' => 'hidden',
      '#value' => $product['id'] ?? NULL,
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Product Name'),
      '#default_value' => $product['name'] ?? '',
      '#required' => TRUE,
    ];

    $form['summary'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Summary'),
      '#default_value' => $product['summary'] ?? '',
      '#required' => TRUE,
    ];

    $form['image'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Image URL'),
      '#default_value' => $product['image'] ?? '',
    ];

    $form['product_of_the_day'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Mark as Product of the Day'),
      '#default_value' => $product['product_of_the_day'] ?? 0,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Product'),
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $database = \Drupal::database();
    $values = $form_state->getValues();
    $product_id = $form_state->getValue('product_id');

    if (!empty($product_id)) {
      // If product_id exists, UPDATE instead of INSERT
      $database->update('potd_products')
        ->fields([
          'name' => $values['name'],
          'summary' => $values['summary'],
          'image' => $values['image'],
          'product_of_the_day' => $values['product_of_the_day'] ?? 0,
        ])
        ->condition('id', $product_id)
        ->execute();
      \Drupal::logger('potd')->notice("Updated Product ID: {$product_id}");
    }
    else {
      // If no product_id exists, INSERT new row
      $database->insert('potd_products')
        ->fields([
          'name' => $values['name'],
          'summary' => $values['summary'],
          'image' => $values['image'],
          'product_of_the_day' => $values['product_of_the_day'] ?? 0,
        ])
        ->execute();
      \Drupal::logger('potd')
        ->notice("New Product Created: " . $values['name']);
    }

    $this->messenger()->addStatus($this->t('Product saved successfully.'));
  }

}