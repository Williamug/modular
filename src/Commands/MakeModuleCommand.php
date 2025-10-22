<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
  protected $signature = 'module:make {name}';
  protected $description = 'Scaffold a new module inside the modules/ directory';

  public function handle(Filesystem $files)
  {
    $name = Str::studly($this->argument('name'));
    $slug = Str::kebab($name);
    $base = base_path("Modules/{$slug}");

    if ($files->isDirectory($base)) {
      $this->error("Module already exists: {$slug}");
      return Command::FAILURE;
    }

    // ✅ Create all required directories
    $dirs = [
      $base,
      "{$base}/Providers",
      "{$base}/Http/Controllers",
      "{$base}/Models",
      "{$base}/Database/migrations",
      "{$base}/routes",
      "{$base}/resources/views",
      "{$base}/resources/js/Pages",
    ];

    foreach ($dirs as $dir) {
      $files->ensureDirectoryExists($dir);
    }

    // ✅ module.json
    $manifest = [
      'name' => $name,
      'slug' => $slug,
      'version' => '1.0.0',
      'description' => "{$name} module",
      'enabled' => true,
      'provider' => "Williamug\\Modules\\{$name}\\Providers\\{$name}ServiceProvider",
    ];

    $files->put(
      "{$base}/module.json",
      json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    );

    // ✅ hooks.php (empty template)
    $hooks = <<<PHP
<?php

use Williamug\\Modular\\HookManager;

return function (HookManager \$hooks) {
    // Example:
    // \$hooks->listen('user.created', fn(\$user) => Log::info("New user: " . \$user->name));
};
PHP;
    $files->put("{$base}/hooks.php", $hooks);

    // ✅ Service Provider (auto-loads hooks, routes, migrations, and views)
    $provider = <<<PHP
<?php

namespace App\\Modules\\{$name}\\Providers;

use Illuminate\\Support\\ServiceProvider;
use Williamug\\Modular\\HookManager;

class {$name}ServiceProvider extends ServiceProvider
{
    protected string \$module = '{$slug}';

    public function boot()
    {
        \$base = base_path('modules/' . \$this->module);

        // Load routes
        if (file_exists(\$base . '/routes/web.php')) {
            \$this->loadRoutesFrom(\$base . '/routes/web.php');
        }

        // Load migrations
        if (is_dir(\$base . '/Database/migrations')) {
            \$this->loadMigrationsFrom(\$base . '/Database/migrations');
        }

        // Load views
        if (is_dir(\$base . '/resources/views')) {
            \$this->loadViewsFrom(\$base . '/resources/views', \$this->module);
        }

        // Load hooks
        \$hookFile = \$base . '/hooks.php';
        if (file_exists(\$hookFile)) {
            \$callback = require \$hookFile;
            if (is_callable(\$callback)) {
                \$callback(app(HookManager::class));
            }
        }
    }
}
PHP;
    $files->put("{$base}/Providers/{$name}ServiceProvider.php", $provider);

    // ✅ Sample route
    $route = <<<PHP
<?php

use Illuminate\\Support\\Facades\\Route;

Route::get('/{$slug}', function () {
    return response("{$name} module loaded successfully.");
});
PHP;
    $files->put("{$base}/routes/web.php", $route);

    $this->info("✅ Module scaffolded: {$slug}");
    return Command::SUCCESS;
  }
}
