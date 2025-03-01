# Banner Module

## Overview
The `banner` module provides a dynamic banner block that displays images based on the current page's menu structure. It determines the appropriate banner by analyzing the active menu item and its top-level parent.

## Features
- Dynamically selects a banner image based on the current URL.
- Retrieves menu hierarchy to find the top parent.
- Supports configurable banner images.
- Caches data efficiently to improve performance.

## Installation
1. Place the `banner` module inside the `modules/custom/` directory of your Drupal installation.
2. Enable the module using Drush or via the Drupal admin panel:
   ```sh
   drush en banner -y
   ```
3. Clear the cache:
   ```sh
   drush cache:rebuild
   ```

## Configuration
1. Once the module is installed, it's create a test content, like pages and menu items
2. The block will be available in the block layout section, and put in a region (content)
2. To test, change a menu item and see the banner change (todo: clear cache auto)


## Dependencies
This module requires the following core services:
- `config.factory`
- `entity_type.manager`
- `menu.link_tree`
- `current_route_match`
- `path_alias.manager`
- `path.current`

## Development Notes
### Key Files:
- `src/Plugin/Block/BannerBlock.php`: Defines the block and retrieves the banner image.
- `src/Services/BannerManager.php`: Contains logic for determining the appropriate banner image.



## Troubleshooting
- **Banner not displaying?**
    - Ensure the block is placed in a visible region.
    - Check that images are configured correctly in the settings, and the exists in public folder.
    - Clear the cache: `drush cache:rebuild`

## License
This module is open-source and distributed under the MIT license.

## Maintainers
- **Jonatan Urrego** (jonurace@gmail.com)

