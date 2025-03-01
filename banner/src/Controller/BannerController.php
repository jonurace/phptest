<?php

namespace Drupal\banner\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\banner\Services\BannerManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for dynamic banner.
 */
class BannerController extends ControllerBase {

  /**
   * service for the banner manager.
   *
   * @var \Drupal\banner\Services\BannerManager
   */
  protected $bannerManager;

  /**
   * Constructor del controlador.
   *
   * @param \Drupal\banner\Services\BannerManager $bannerManager
   *   The admin manager service.
   */
  public function __construct(BannerManager $bannerManager) {
    $this->bannerManager = $bannerManager;
  }

  /**
   * Method to create the controller.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   the container interface.
   *
   * @return static
   *   Return the controller.
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('banner.banner_manager'));
  }

  /**
   * Render the content of the banner pages
   *
   * @return array
   *   Render array.
   */
  public function content() {
    return [
      '#markup' => '<p>Welcome to the site</p>',
    ];
  }

}