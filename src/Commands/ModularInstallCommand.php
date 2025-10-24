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
            $viteConfigPath = base_path('vite.config.js');
            if ($files->exists($viteConfigPath)) {
                $viteConfigContent = $files->get($viteConfigPath);

                if (! str_contains($viteConfigContent, 'Modules')) {
                    $viteConfigContent .= "\n\n".$this->getViteConfigSnippet();
                    $files->put($viteConfigPath, $viteConfigContent);
                    $this->info('Updated vite.config.js to include module assets.');
                } else {
                    $this->comment('vite.config.js already includes module assets.');
                }
            } else {
                $this->error('vite.config.js not found. Please ensure you are using Vite.');
            }

            // Modify Blade template
            $bladePath = resource_path('views/layouts/app.blade.php');
            if ($files->exists($bladePath)) {
                $bladeContent = $files->get($bladePath);

                if (! str_contains($bladeContent, '@foreach ($modules as $module)')) {
                    $bladeContent = str_replace('</head>', $this->getBladeSnippet()."\n</head>", $bladeContent);
                    $files->put($bladePath, $bladeContent);
                    $this->info('Updated app.blade.php to include module assets.');
                } else {
                    $this->comment('app.blade.php already includes module assets.');
                }
            } else {
                $this->error('Blade template not found at resources/views/layouts/app.blade.php.');
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
