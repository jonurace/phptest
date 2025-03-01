<?php

namespace Drupal\banner\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Core\Path\CurrentPathStack;

class BannerManager {

  protected $configFactory;

  protected $entityTypeManager;

  protected $menuLinkTree;

  protected $routeMatch;

  protected $aliasManager;

  protected $currentPath;

  public function __construct(
    ConfigFactoryInterface $configFactory,
    EntityTypeManagerInterface $entityTypeManager,
    MenuLinkTreeInterface $menuLinkTree,
    RouteMatchInterface $routeMatch,
    AliasManagerInterface $aliasManager,
    CurrentPathStack $currentPath
  ) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
    $this->menuLinkTree = $menuLinkTree;
    $this->routeMatch = $routeMatch;
    $this->aliasManager = $aliasManager;
    $this->currentPath = $currentPath;
  }

  public function getBannerImage() {
    $current_path = $this->currentPath->getPath();
    $current_path_alias = $this->aliasManager->getAliasByPath($current_path) ?? $current_path;

    $menu_name = 'main';

    // Obtener imágenes configuradas
    $config = $this->configFactory->get('banner.settings');
    $image_a = $config->get('banner_a');
    $image_b = $config->get('banner_b');

    // Obtener y procesar el menú
    $parameters = new MenuTreeParameters();
    $menu_tree = $this->menuLinkTree->load($menu_name, $parameters);
    $indexed_menu = $this->buildMenuIndex($menu_tree);

    // Buscar el elemento activo o su ancestro más alto
    $active_item = $this->findActiveMenuItem($indexed_menu, $current_path_alias);

    if (!$active_item) {
      return '';
    }

    $top_parent = $this->findTopParent($active_item, $indexed_menu);

    if ($top_parent) {
      $menu_url = $top_parent->link->getUrlObject()?->toString() ?? '';

      if ($menu_url) {
        if (strpos($menu_url, '/root-a') !== FALSE) {
          return $image_a;
        }
        if (strpos($menu_url, '/root-b') !== FALSE) {
          return $image_b;
        }
      }
    }

    return '';
  }

  private function buildMenuIndex(array $menu_tree) {
    $indexed_menu = [];
    foreach ($menu_tree as $item) {
      $indexed_menu[$item->link->getPluginId()] = $item;
      if (!empty($item->subtree)) {
        $indexed_menu += $this->buildMenuIndex($item->subtree);
      }
    }
    return $indexed_menu;
  }

  private function findActiveMenuItem(array $indexed_menu, string $current_path_alias) {
    $best_match = NULL;
    $best_match_length = 0;

    foreach ($indexed_menu as $item) {
      $menu_url = $item->link->getUrlObject()?->toString();
      if (!$menu_url) {
        continue;
      }

      $menu_url_path = trim(parse_url($menu_url, PHP_URL_PATH), '/');
      $current_path_clean = trim(parse_url($current_path_alias, PHP_URL_PATH), '/');

      // Si la URL del menú está contenida dentro del alias actual
      if (strpos($current_path_clean, $menu_url_path) === 0) {
        $match_length = strlen($menu_url_path);

        // Buscar la coincidencia más larga (más específica)
        if ($match_length > $best_match_length) {
          $best_match = $item;
          $best_match_length = $match_length;
        }
      }
    }

    if ($best_match) {
      return $best_match;
    }
    return NULL;
  }

  private function findTopParent($item, $indexed_menu) {
    if (!$item) {
      return NULL;
    }

    $visited_parents = [];

    while ($item->link->getParent() && isset($indexed_menu[$item->link->getParent()])) {
      $parent_id = $item->link->getParent();
      $visited_parents[] = $parent_id;

      if ($parent_id === 'standard.front_page') {
        \Drupal::logger('banner')
          ->warning('Parent <front> ');
        break;
      }

      $item = $indexed_menu[$parent_id];
    }

    return $item;
  }

}