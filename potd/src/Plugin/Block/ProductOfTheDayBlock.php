<?php

declare(strict_types=1);

namespace Drupal\potd\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a 'Product of the Day' block.
 *
 * @Block(
 *   id = "potd_product_block",
 *   admin_label = @Translation("Product of the Day")
 * )
 */
class ProductOfTheDayBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $database = Database::getConnection();
    $config = \Drupal::config('potd.settings');

    // Get configuration settings
    $title = $config->get('block_title') ?: 'Product of the Day';
    $max_potd = (int) ($config->get('max_potd_products') ?: 5);

    // Fetch products marked as "Product of the Day"
    $query = $database->select('potd_products', 'p')
      ->fields('p', ['id', 'name', 'summary', 'image'])
      ->condition('product_of_the_day', 1)
      ->orderBy('id', 'DESC')
      ->range(0, $max_potd)
      ->execute()
      ->fetchAllAssoc('id');

    // If no products found, return default message
    if (empty($query)) {
      \Drupal::logger('potd')
        ->notice('No products found for Product of the Day.');
      return [
        '#markup' => $this->t('No product of the day available.'),
      ];
    }
    $database = Database::getConnection();
    $config = \Drupal::config('potd.settings');

    // Get configuration settings
    $title = $config->get('block_title') ?: 'Product of the Day';
    $max_potd = (int) ($config->get('max_potd_products') ?: 5);

    // Fetch products marked as "Product of the Day"
    $query = $database->select('potd_products', 'p')
      ->fields('p', ['id', 'name', 'summary', 'image'])
      ->condition('product_of_the_day', 1)
      ->orderBy('id', 'DESC')
      ->range(0, $max_potd)
      ->execute()
      ->fetchAllAssoc('id');

    $items = [];
    $product_ids = [];

    foreach ($query as $product) {
      // Ensure no duplicates
      if (!in_array($product->id, $product_ids)) {
        $items[] = [
          'id' => (int) $product->id,
          'name' => (string) $product->name,
          'summary' => (string) $product->summary,
          'image' => !empty($product->image) ? (string) $product->image : 'https://via.placeholder.com/150',
          'cta_link' => Link::fromTextAndUrl(
            $this->t('View Product'),
            Url::fromRoute('potd.product_list', ['product_id' => $product->id], ['absolute' => TRUE])
          )->toString(),
        ];
        $product_ids[] = $product->id; // Track displayed products
      }
    }

    return [
      '#theme' => 'potd_product_block',
      '#title' => $this->configuration['label'] ?? $config->get('block_title') ?? $this->t('Product of the Day'),
      '#products' => $items,
      '#cache' => ['max-age' => 0],
    ];

  }
}