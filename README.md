[![Latest Version on Packagist](https://img.shields.io/packagist/v/williamug/modular.svg?style=flat-square)](https://packagist.org/packages/williamug/modular)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/williamug/modular/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/williamug/modular/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/williamug/modular/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/williamug/modular/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/williamug/modular.svg?style=flat-square)](https://packagist.org/packages/williamug/modular)

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
php artisan module:create Blog
```
This will create a `Blog` module in the `Modules` directory with the following structure:
```
Modules/
  Blog/
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
php artisan module:enable Blog
```
Disable a module:
```bash
php artisan module:disable Blog
```

### Deleting a Module
Delete a module:
```bash
php artisan module:delete Blog
```

### Running Migrations
Run migrations for a specific module:
```bash
php artisan module:migrate Blog
```

### Seeding Data
Seed data for a specific module:
```bash
php artisan module:seed Blog
```

### Publishing Assets
Publish assets for a specific module:
```bash
php artisan module:publish Blog
```

### Generating Files
Generate a controller within a module:
```bash
php artisan module:controller Blog PostController
```
Generate a model within a module:
```bash
php artisan module:model Blog Post --migration
```
Generate a migration within a module:
```bash
php artisan module:migration Blog create_posts_table
```

### Viewing Module Information
View detailed information about a module:
```bash
php artisan module:info Blog
```

## Example Project

### Setting Up a Blog Module
1. **Create the Module**:
   ```bash
   php artisan module:make Blog
   ```

2. **Add Routes**:
   Edit `Modules/Blog/routes/web.php`:
   ```php
   <?php

   use Illuminate\Support\Facades\Route;

   Route::get('/blog', function () {
       return 'Welcome to the Blog module!';
   });
   ```

3. **Create a Controller**:
   ```bash
   php artisan module:controller Blog BlogController
   ```
   Edit `Modules/Blog/Http/Controllers/BlogController.php`:
   ```php
   <?php

   namespace Modules\Blog\Http\Controllers;

   use Illuminate\Http\Request;
   use App\Http\Controllers\Controller;

   class BlogController extends Controller
   {
       public function index()
       {
           return 'Blog index page';
       }
   }
   ```

4. **Add a Model**:
   ```bash
   php artisan module:model Blog Post --migration
   ```
   Edit the generated migration file to define the `posts` table schema.

5. **Run Migrations**:
   ```bash
   php artisan module:migrate Blog
   ```

6. **Enable the Module**:
   ```bash
   php artisan module:enable Blog
   ```

7. **Access the Module**:
   Visit `/blog` in your browser to see the Blog module in action.

## API-Only Projects
For API-only projects, the package automatically skips frontend scaffolding. You can still use all the commands to manage modules and their backend logic.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue to discuss changes.

## License
This package is open-source software licensed under the [MIT license](LICENSE.md).
