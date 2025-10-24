<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModularInstallCommand extends Command
{
  protected $signature = 'modular:install';

  protected $description = 'Set up the modular package by modifying necessary files';

  public function handle(Filesystem $files)
  {
    $this->info('Starting modular package installation...');

    // Detect if the project is API-only
    $isApiOnly = ! $files->exists(resource_path('js')) && ! $files->exists(resource_path('views'));

    if (! $isApiOnly) {
      // Modify vite.config.ts/js
      $viteConfigPathJs = base_path('vite.config.js');
      $viteConfigPathTs = base_path('vite.config.ts');
      $viteConfigPath = $files->exists($viteConfigPathJs) ? $viteConfigPathJs : ($files->exists($viteConfigPathTs) ? $viteConfigPathTs : null);
      if ($viteConfigPath) {
        $viteConfigContent = $files->get($viteConfigPath);

        if (! str_contains($viteConfigContent, 'Modules')) {
          $viteConfigContent .= "\n\n" . $this->getViteConfigSnippet();
          $files->put($viteConfigPath, $viteConfigContent);
          $this->info('Updated ' . basename($viteConfigPath) . ' to include module assets.');
        } else {
          $this->comment(basename($viteConfigPath) . ' already includes module assets.');
        }
      } else {
        $this->error('vite.config.js or vite.config.ts not found. Please ensure you are using Vite.');
      }

      // Modify Blade template
      $bladePaths = [
        resource_path('views/layouts/app.blade.php'),
        resource_path('views/app.blade.php'),
      ];
      $bladePath = null;
      foreach ($bladePaths as $path) {
        if ($files->exists($path)) {
          $bladePath = $path;
          break;
        }
      }
      if ($bladePath) {
        $bladeContent = $files->get($bladePath);

        if (! str_contains($bladeContent, '@foreach ($modules as $module)')) {
          $bladeContent = str_replace('</head>', $this->getBladeSnippet() . "\n</head>", $bladeContent);
          $files->put($bladePath, $bladeContent);
          $this->info('Updated ' . basename($bladePath) . ' to include module assets.');
        } else {
          $this->comment(basename($bladePath) . ' already includes module assets.');
        }
      } else {
        $this->error('app.blade.php not found in resources/views/layouts or resources/views.');
      }
    } else {
      $this->comment('Skipping frontend modifications as this appears to be an API-only project.');
    }

    $this->info('Modular package installation completed.');
  }

  protected function getViteConfigSnippet(): string
  {
    return <<<JS
// Scan Modules directory for module-assets.json
const moduleAssets = [];
const modulesPath = path.resolve(__dirname, 'Modules');
if (fs.existsSync(modulesPath)) {
    fs.readdirSync(modulesPath).forEach((module) => {
        const assetsPath = path.join(modulesPath, module, 'module-assets.json');
        if (fs.existsSync(assetsPath)) {
            const assets = JSON.parse(fs.readFileSync(assetsPath, 'utf-8'));
            if (assets.js) {
                assets.js.forEach((js) => moduleAssets.push(`Modules/\${module}/\${js}`));
            }
            if (assets.css) {
                assets.css.forEach((css) => moduleAssets.push(`Modules/\${module}/\${css}`));
            }
        }
    });
}
JS;
  }

  protected function getBladeSnippet(): string
  {
    return <<<'BLADE'
@foreach ($modules as $module)
    @vite(["Modules/{$module}/resources/js/app.js", "Modules/{$module}/resources/css/app.css"])
@endforeach
BLADE;
  }
}
