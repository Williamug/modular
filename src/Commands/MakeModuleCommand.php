<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
  protected $signature = 'module:create {names*}';

  protected $description = 'Scaffold one or more modules inside the modules/ directory';

  protected function getStub(string $stubName): string
  {
    return file_get_contents(__DIR__ . '/../stubs/' . $stubName);
  }

  protected function detectStarterKit(): string
  {
    $packageJsonPath = base_path('../modular-test/package.json');
    if (file_exists($packageJsonPath)) {
      $packageJson = json_decode(file_get_contents($packageJsonPath), true);
      $deps = array_merge(
        $packageJson['dependencies'] ?? [],
        $packageJson['devDependencies'] ?? []
      );
      if (isset($deps['vue']) || isset($deps['@vitejs/plugin-vue'])) {
        return 'vue';
      }
      if (isset($deps['react']) || isset($deps['@vitejs/plugin-react'])) {
        return 'react';
      }
      if (isset($deps['livewire']) || isset($deps['@livewire/livewire'])) {
        return 'livewire';
      }
    }
    return 'vue'; // Default to vue if undetectable
  }

  public function handle(Filesystem $files)
  {
    $names = $this->argument('names');

    foreach ($names as $name) {
      $name = Str::studly($name);
      $slug = Str::kebab($name);
      $base = base_path("Modules/{$name}");

      if ($files->isDirectory($base)) {
        $this->error("Module already exists: {$name}");

        continue;
      }

      // ✅ Create all required directories
      $dirs = [
        $base,
        "{$base}/app/Providers",
        "{$base}/app/Http/Controllers",
        "{$base}/app/Actions",
        "{$base}/app/Models",
        "{$base}/database/migrations",
        "{$base}/databasectories",
        "{$base}/database/seeders",
        "{$base}/routes",
        "{$base}/resources/views",
        "{$base}/resources/js/Pages",
      ];

      foreach ($dirs as $dir) {
        $files->ensureDirectoryExists($dir);
      }

      // Detect if the project is API-only
      $isApiOnly = ! $files->exists(resource_path('js')) && ! $files->exists(resource_path('views'));

      if (! $isApiOnly) {
        // ✅ Create frontend directories
        $frontendDirs = [
          "{$base}/resources/js",
          "{$base}/resources/css",
        ];
        foreach ($frontendDirs as $dir) {
          $files->ensureDirectoryExists($dir);
        }

        // ✅ Create module-assets.json
        $moduleAssets = [
          'js' => ['resources/js/app.js'],
          'css' => ['resources/css/app.css'],
        ];
        $files->put("{$base}/module-assets.json", json_encode($moduleAssets, JSON_PRETTY_PRINT));

        // ✅ Create default JS and CSS files
        $starterKit = $this->detectStarterKit();
        $jsStubName = match ($starterKit) {
          'vue' => 'app-js.stub',
          'react' => 'app-js-react.stub',
          'livewire' => 'app-js-livewire.stub',
          default => 'app-js.stub',
        };
        $jsStub = $this->getStub($jsStubName);
        $jsContent = str_replace('{{module}}', $name, $jsStub);
        $files->put("{$base}/resources/js/app.js", $jsContent);
        $files->put("{$base}/resources/css/app.css", "/* CSS for {$name} module */");
      } else {
        $this->comment("Skipping frontend scaffolding for module: {$name} as this appears to be an API-only project.");
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

      // ✅ Hooks
      $hooksStub = $this->getStub('hooks.stub');
      $files->put("{$base}/hooks.php", $hooksStub);

      // ✅ Service Provider
      $providerStub = $this->getStub('service-provider.stub');
      $providerContent = str_replace(['{{module}}', '{{slug}}'], [$name, $slug], $providerStub);
      $files->put("{$base}/Providers/{$name}ServiceProvider.php", $providerContent);

      // ✅ Routes
      $routeStub = $this->getStub('route.stub');
      $routeContent = str_replace(['{{module}}', '{{slug}}'], [$name, $slug], $routeStub);
      $files->put("{$base}/routes/web.php", $routeContent);

      // ✅ Default Controller
      $controllerStub = $this->getStub('controller.stub');
      $controllerContent = str_replace('{{module}}', $name, $controllerStub);
      $files->put("{$base}/Http/Controllers/{$name}Controller.php", $controllerContent);

      // ✅ Default View
      $viewStub = $this->getStub('view.stub');
      $viewContent = str_replace('{{module}}', $name, $viewStub);
      $files->put("{$base}/resources/views/index.blade.php", $viewContent);

      // ✅ Default Migration
      $migrationStub = $this->getStub('migration.stub');
      $migrationContent = str_replace('{{table}}', $slug, $migrationStub);
      $timestamp = date('Y_m_d_His');
      $files->put("{$base}/Database/migrations/{$timestamp}_create_{$slug}_table.php", $migrationContent);

      $this->info("✅ Module scaffolded: {$name}");
    }

    return Command::SUCCESS;
  }
}
