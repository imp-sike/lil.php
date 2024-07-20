# Lil.php
<img src="logo.png" alt="LilPHP Logo" width="150"/>


**Lil.php** is a lightweight PHP framework designed to simplify the development of web applications with a focus on ease of use and flexibility. It follows the MVC architecture and provides built-in support for routing, view rendering, model handling, and more.

## Features

- **MVC Architecture**: A structured approach to building web applications.
- **Dynamic Routing**: Easy route definition and handling.
- **Model Handling**: Simple database interactions and CRUD operations.
- **View Rendering**: Helper functions for rendering views and handling JSON responses.
- **Middleware Support**: Flexible middleware integration.
- **File Management**: Utilities for file upload, deletion, and updates.

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yourusername/lil.php.git
   cd lil.php
   ```

2. **Install Lil.php**

   Run the following command to install Lil.php:

   ```bash
   php cli update
   ```

   This command installs only the necessary Lil.php files in the `lil` folder, leaving other files untouched.

3. **Adding Features (COMING SOON)**

   To add additional features (batteries), use:

   ```bash
   php cli add <battery_name>
   ```

   Replace `<battery_name>` with the name of the feature you want to add.

## Configuration

After installation, update your project configuration:

1. **Update `.htaccess`**

   Set the `RewriteBase` to match the folder where your project is located. For example:

   ```apache
   RewriteBase /stevel
   ```

   This ensures that URLs are correctly rewritten to your application’s entry point.

2. **Configure Database and Base URI**

   Edit `config/ConfigClass.php` to match your environment settings:

   ```php
   <?php

   namespace App\Config;
   use Lil\Model;

   class ConfigClass {
       public static $db_host = "localhost";
       public static $db_name = "stevel_01";
       public static $db_port = "3306";
       public static $db_uname = "root";
       public static $db_pass = "";
       public static $base_uri = "/stevel";
   }
   ```

## Documentation

For detailed documentation on how to use Lil.php, visit our [documentation site](https://lil-php-docs-mmoc.vercel.app/).

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

If you have any questions or need support, feel free to open an issue on our [GitHub repository](https://github.com/imp-sike/lil.php/issues).
