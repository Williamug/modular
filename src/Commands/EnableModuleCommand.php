<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class EnableModuleCommand extends Command
{
    protected $signature = 'module:enable {module}';

    protected $description = 'Enable a specific module';

    public function handle(Filesystem $files)
    {
        $module = $this->argument('module');
        $modulePath = base_path("Modules/{$module}/module.json");

        if (! $files->exists($modulePath)) {
            $this->error("Module not found: {$module}");

            return Command::FAILURE;
        }

        $moduleConfig = json_decode($files->get($modulePath), true);
        $moduleConfig['enabled'] = true;
        $files->put($modulePath, json_encode($moduleConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info("Module enabled: {$module}");

        return Command::SUCCESS;
    }
}
