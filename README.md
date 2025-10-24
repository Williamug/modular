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

## Modular Navigation

Modules can register navigation items by adding a `navigation` key to their `module.json`. You can use advanced features like icons, groupings, and permissions:

```json
{
  "name": "Customers",
  "slug": "customers",
  "enabled": true,
  "navigation": [
    { "label": "Customers", "url": "/customers", "icon": "fa fa-users", "group": "CRM", "permission": "view-customers" },
    { "label": "Invoices", "url": "/invoices", "icon": "fa fa-file-invoice", "group": "Accounting", "permission": "view-invoices" }
  ]
}
```

### Helper Usage

In your sidebar Blade view, use the helper:

```blade
<ul>
    @foreach(modular_navigation() as $item)
        @if(!$item['permission'] || auth()->user()?->can($item['permission']))
            <li>
                @if($item['icon'])<i class="{{ $item['icon'] }}"></i>@endif
                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            </li>
        @endif
    @endforeach
</ul>
```

#### Grouped Navigation Example

```blade
@php
    $groups = [];
    foreach(modular_navigation() as $item) {
        if(!$item['permission'] || auth()->user()?->can($item['permission'])) {
            $groups[$item['group'] ?? 'Other'][] = $item;
        }
    }
@endphp
<ul>
    @foreach($groups as $group => $items)
        <li class="nav-group">
            <span>{{ $group }}</span>
            <ul>
                @foreach($items as $item)
                    <li>
                        @if($item['icon'])<i class="{{ $item['icon'] }}"></i>@endif
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>
```

### Blade Directive Usage

Or use the Blade directive for a concise syntax:

```blade
<ul>
    @modularNavigation
</ul>
```

This will automatically render grouped navigation items from all enabled modules, showing icons and respecting permissions.

## Modular Content Injection

Modules can inject custom content into parent layouts or pages (e.g., settings, dashboard widgets, or any slot) by adding a `settings`, `widgets`, or `content` key to their `module.json`:

```json
{
  "name": "Customers",
  "slug": "customers",
  "enabled": true,
  "settings": [
    { "label": "Customer Settings", "view": "Modules/customer/resources/views/settings.blade.php", "icon": "fa fa-cog", "group": "CRM", "permission": "manage-customers" }
  ],
  "widgets": [
    { "label": "Customer Stats", "view": "Modules/customer/resources/views/widgets/stats.blade.php", "icon": "fa fa-chart-bar", "group": "CRM", "permission": "view-customers" }
  ],
  "content": [
    { "label": "Promo Banner", "view": "Modules/customer/resources/views/banner.blade.php", "icon": "fa fa-bullhorn", "group": "Marketing", "permission": "view-banner" }
  ]
}
```

### Helper Usage for Settings

In your unified settings page:

```blade
@foreach(modular_settings() as $setting)
    @if(!$setting['permission'] || auth()->user()?->can($setting['permission']))
        @if($setting['icon'])<i class="{{ $setting['icon'] }}"></i>@endif
        @include($setting['view'])
    @endif
@endforeach
```
Or use the Blade directive:
```blade
@modularSettings
```

### Helper Usage for Widgets

In your dashboard:

```blade
@foreach(modular_widgets() as $widget)
    @if(!$widget['permission'] || auth()->user()?->can($widget['permission']))
        @if($widget['icon'])<i class="{{ $widget['icon'] }}"></i>@endif
        @include($widget['view'])
    @endif
@endforeach
```
Or use the Blade directive:
```blade
@modularWidgets
```

### Helper Usage for Generic Content

In any parent layout or page:

```blade
@foreach(modular_content() as $content)
    @if(!$content['permission'] || auth()->user()?->can($content['permission']))
        @if($content['icon'])<i class="{{ $content['icon'] }}"></i>@endif
        @include($content['view'])
    @endif
@endforeach
```
Or use the Blade directive:
```blade
@modularContent
```

### Example Module Content (banner.blade.php)

```blade
{{-- Modules/customer/resources/views/banner.blade.php --}}
<div class="module-banner">
    <h4>Special Promotion!</h4>
    <p>Get 20% off for all new customers this month.</p>
</div>
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
