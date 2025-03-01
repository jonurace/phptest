# Product of the Day (POTD) - Drupal 10 Module

## Overview
The **Product of the Day (POTD)** module provides an admin interface to manage products and allows site administrators to highlight specific products as "Product of the Day." The module also includes a block that displays a randomly selected highlighted product.

## Features
- **Admin panel to manage products** (add, edit, delete)
- **Custom database table (`potd_products`)** to store product information
- **Mark products as "Product of the Day"** (up to 5 active at a time)
- **Custom block to display a random "Product of the Day"**
- **Admin configuration page to set block title and maximum active products**

## Installation
1. Place the `potd` module inside `modules/custom/` in your Drupal installation.
2. Run the following command to enable the module:
   ```sh
   drush en potd -y
   ```
3. Clear the cache to ensure proper loading:
   ```sh
   drush cr
   ```
4. The module is now installed and ready to use.

## Configuration
### Admin Panel
Navigate to **`/admin/config/potd/products`** to:
- View a list of existing products
- Add new products
- Edit or delete products

### Block Configuration
The "Product of the Day" block can be placed in any region via **Structure > Block layout** (`/admin/structure/block`).

### Module Settings
A configuration page is available at **`/admin/config/potd/settings`**, where administrators can:
- Set the block title
- Define the maximum number of "Product of the Day" items

## Database Structure
The module creates the following database table:
### `potd_products`
| Column               | Type         | Description                         |
|----------------------|-------------|-------------------------------------|
| `id`                | INT (Auto)   | Primary key                         |
| `name`              | VARCHAR(255) | Product name                        |
| `summary`           | TEXT         | Product summary                     |
| `image`             | VARCHAR(255) | Image URL                           |
| `product_of_the_day`| TINYINT(1)   | 1 = Highlighted, 0 = Regular        |
| `created_at`        | TIMESTAMP    | Date when the product was added     |

## Usage
1. **Add Products**: Navigate to **`/admin/config/potd/products`** and click "Add Product."
2. **Highlight as Product of the Day**: While adding/editing a product, check the "Product of the Day" option.
3. **Display on Frontend**: Place the "Product of the Day" block in any region via **Structure > Block layout**.

## Uninstallation
To remove the module completely:
```sh
 drush pm:uninstall potd -y
```
This will delete all configuration and database tables associated with the module.

## License
This module is open-source and licensed under the GNU General Public License v2 or later.

