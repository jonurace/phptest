# Technical Assessment - Custom Module

## Overview

This repository contains two custom Drupal 10 modules: `banner` and `potd`.

- **`banner` Module**: Provides a dynamic banner block that displays images based on the current page’s menu structure. It determines the appropriate banner by analyzing the active menu item and its top-level parent. Spend time 2.5 Hours
- **`potd` (Product of the Day) Module**: Provides a simple block for displaying the "Product of the Day" on a designated page (e.g., homepage). This module operates entirely server-side, storing product selections in a dedicated database table. Spend time 1.5 Hours

## Requirements

These modules are designed for **Drupal 10** and require the following dependencies:

- PHP 8.1+
- MySQL/MariaDB
- Drupal 10.x

## Tools Used

Development and testing were performed using the following tools:

- **[PHPStorm](https://www.jetbrains.com/phpstorm/)** – Advanced IDE for PHP and Drupal development.
- **[Turbo Drupal](https://github.com/droptica/turbo-drupal)** – A streamlined Drupal starter kit with performance optimizations.
- **[Composer](https://getcomposer.org/)** – Dependency management for PHP and Drupal modules.
- **[Drush](https://www.drush.org/)** – Command-line tool for managing Drupal installations.
- **[DDEV](https://ddev.com/)** – Local development environment for Drupal projects.
- **[Xdebug](https://xdebug.org/)** – PHP debugging and profiling tool for efficient development.
- **[copilot](https://copilot.github.com/)** – AI-powered code completion tool for PHP and Drupal development.

## Installation

### 1. Set Up the Environment
Ensure you have **DDEV** installed and running:

```sh
 ddev start
```

### 2. Install Dependencies
Run Composer to install necessary dependencies:

```sh
composer install
```

### 3. Enable the Modules
Use Drush to enable both `banner` and `potd` modules:

```sh
drush en banner potd -y
```

### 4. Clear the Cache
```sh
drush cr
```

## Usage

### `banner` Module
1. Configure banners via the Drupal admin interface.
2. Assign banners to specific menu structures.
3. Activte
```sh
ddev drush en banner -y
```

### `potd` Module
1. Assign a product to be displayed as "Product of the Day."
2. The product will be updated daily based on the configured logic.
3. 3. Activte
```sh
ddev drush en potd -y
```

## Development

For debugging, enable Xdebug with DDEV:

```sh
ddev xdebug on
```

For Drush commands:

```sh
drush status
```

To check logs:

```sh
drush ws
```

## License

This project is licensed under the [MIT License](LICENSE).
