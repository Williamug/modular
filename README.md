[![Latest Version on Packagist](https://img.shields.io/packagist/v/williamug/modular.svg?style=flat-square)](https://packagist.org/packages/williamug/modular/stats#major/all)
[![run-tests](https://github.com/Williamug/modular/actions/workflows/run-tests.yml/badge.svg)](https://github.com/williamug/modular/actions/workflows/run-tests.yml)
[![Made With](https://img.shields.io/badge/made_with-php-blue)](/docs/requirements/)
[![License](https://img.shields.io/packagist/l/williamug/modular.svg)](https://github.com/williamug/modular/blob/master/LICENSE.txt)
<!-- [![Total Downloads](https://img.shields.io/packagist/dt/williamug/modular.svg?style=flat-square)](https://packagist.org/packages/williamug/modular) -->

# Modular

The Modular provides a modular architecture for Laravel applications, allowing you to organize your application into self-contained modules. This package is inspired by the concept of modular development, making it easier to manage large applications.


## Features
- Create, enable, disable, and delete modules.
- Run migrations, seeders, and publish assets for specific modules.
- Generate controllers, models, and migrations within modules.
- Dynamic module loading and management.
- Supports both API-only and full-stack Laravel projects.

## Installation

Install the package via Composer:
```bash
composer require williamug/modular
```

Run the installation command to set up the package:
```bash
php artisan modular:install
```
This will publish the configuration file and configure your frontend.

## Available Commands

### Creating a Module
To create a new module, use the `module:create` command:
```bash
php artisan module:create Expense
```
This will create a `Expense` module in the `Modules` directory with the following structure:
```
Modules/
  Expense/
    Providers/
    Http/
    Models/
    Database/
    routes/
    resources/
    hook.php
    module.json
```

### Enabling and Disabling Modules
Enable a module:
```bash
php artisan module:enable Expense
```
Disable a module:
```bash
php artisan module:disable Expense
```

### Deleting a Module
Delete a module:
```bash
php artisan module:delete Expense
```

### Running Migrations
Run migrations for a specific module:
```bash
php artisan module:migrate Expense
```

### Seeding Data
Seed data for a specific module:
```bash
php artisan module:seed Expense
```

### Publishing Assets
Publish assets for a specific module:
```bash
php artisan module:publish Expense
```

### Generating Files
Generate a controller within a module:
```bash
php artisan module:controller Expense PostController
```
Generate a model within a module:
```bash
php artisan module:model Expense Post --migration
```
Generate a migration within a module:
```bash
php artisan module:migration Expense create_posts_table
```

### Viewing Module Information
View detailed information about a module:
```bash
php artisan module:info Expense
```

## Example Project

### Setting Up a Expense Module
1. **Create the Module**:
   ```bash
   php artisan module:make Expense
   ```

2. **Add Routes**:
   Edit `Modules/Expense/routes/web.php`:
   ```php
   <?php

   use Illuminate\Support\Facades\Route;

   Route::get('/expense', function () {
       return 'Welcome to the Expense module!';
   });
   ```

3. **Create a Controller**:
   ```bash
   php artisan module:controller Expense ExpenseController
   ```
   Edit `Modules/Expense/Http/Controllers/ExpenseController.php`:
   ```php
   <?php

   namespace Modules\Expense\Http\Controllers;

   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;

   class ExpenseController extends Controller
   {
       public function index()
       {
           return 'Expense index page';
       }
   }
   ```

4. **Add a Model**:
   ```bash
   php artisan module:model Expense Post --migration
   ```
   Edit the generated migration file to define the `posts` table schema.

5. **Run Migrations**:
   ```bash
   php artisan module:migrate Expense
   ```

6. **Enable the Module**:
   ```bash
   php artisan module:enable Expense
   ```

7. **Access the Module**:
   Visit `/expense` in your browser to see the Expense module in action.

## API-Only Projects
For API-only projects, the package automatically skips frontend scaffolding. You can still use all the commands to manage modules and their backend logic.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue to discuss changes.

## License
This package is open-source software licensed under the [MIT license](LICENSE.md).
