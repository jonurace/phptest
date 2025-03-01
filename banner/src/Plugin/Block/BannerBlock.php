<?php

namespace Drupal\banner\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\banner\Services\BannerManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * create a block to show a dynamic banner.
 *
 * @Block(
 *   id = "banner_block",
 *   admin_label = @Translation("Banner dinámico"),
 * )
 */
class BannerBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Service for the banner manager.
   *
   * @var \Drupal\banner\Services\BannerManager
   */
  protected $bannerManager;

  /**
   * Constructor of the block.
   *
   * @param array $configuration
   *   Configuration of the plugin
   * @param string $plugin_id
   *   ID of plugin.
   * @param mixed $plugin_definition
   *   Definition of plugin.
   * @param \Drupal\banner\Services\BannerManager $bannerManager
   *   The service for the banner manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, BannerManager $bannerManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->bannerManager = $bannerManager;
  }

  /**
   * Dependency injection.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container for drupal service
   * @param array $configuration
   *   Configuration of the plugin.
   * @param string $plugin_id
   *   ID of plugin.
   * @param mixed $plugin_definition
   *   Definition of plugin.
   *
   * @return static
   *   Return an instance of the block.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('banner.banner_manager')
    );
  }

  /**
   * Construct the block.
   */
  public function build() {
    $image_url = $this->bannerManager->getBannerImage();

    if (!$image_url) {
      return [
        '#markup' => $this->t('There is no image available.'),
      ];
    }

    return [
      '#theme' => 'image',
      '#uri' => $image_url,
      '#alt' => 'Dynamic banner',
      '#attributes' => ['class' => ['banner-image']],
    ];
  }

  /**
   * Get cache contexts.
   */
  public function getCacheContexts() {
    return ['route'];
  }

}