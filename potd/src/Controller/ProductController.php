<?php

declare(strict_types=1);

namespace Drupal\potd\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller for managing products.
 */
class ProductController extends ControllerBase {

  /**
   * Displays a list of products.
   */
  public function productList(): array {
    $database = Database::getConnection();

    // Define the table header.
    $header = [
      ['data' => $this->t('Product Name')],
      ['data' => $this->t('Summary')],
      ['data' => $this->t('Image')],
      ['data' => $this->t('Product of the Day')],
      ['data' => $this->t('Operations')],
    ];

    // Fetch products from the database.
    $query = $database->select('potd_products', 'p')
      ->fields('p', ['id', 'name', 'summary', 'image', 'product_of_the_day'])
      ->execute();

    $rows = [];
    foreach ($query as $record) {
      $edit_link = Link::fromTextAndUrl(
        $this->t('Edit'),
        Url::fromRoute('potd.product_form', ['product_id' => $record->id])
      )->toString();

      $delete_link = Link::fromTextAndUrl(
        $this->t('Delete'),
        Url::fromRoute('potd.product_delete', ['product_id' => $record->id])
      )->toString();

      $rows[] = [
        'data' => [
          $record->name,
          $record->summary,
          ['data' => ['#markup' => '<img src="' . $record->image . '" width="50" />']],
          $record->product_of_the_day ? $this->t('Yes') : $this->t('No'),
          ['data' => ['#markup' => $edit_link . ' | ' . $delete_link]],
        ],
      ];
    }

    $build['add_product'] = [
      '#type' => 'markup',
      '#markup' => Link::fromTextAndUrl(
        $this->t('Add Product'),
        Url::fromRoute('potd.product_form', [], ['absolute' => TRUE]) // Eliminamos 'product_id'
      )->toString(),
      '#prefix' => '<div class="potd-add-product">',
      '#suffix' => '</div>',
    ];

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No products available.'),
    ];

    return $build;
  }

  /**
   * Deletes a product.
   */
  public function deleteProduct(int $product_id): RedirectResponse {
    $database = Database::getConnection();
    $database->delete('potd_products')
      ->condition('id', $product_id)
      ->execute();

    $this->messenger()->addMessage($this->t('Product deleted successfully.'));
    return new RedirectResponse(Url::fromRoute('potd.product_list')
      ->toString());
  }

}