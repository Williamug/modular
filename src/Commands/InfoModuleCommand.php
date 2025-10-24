<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InfoModuleCommand extends Command
{
    protected $signature = 'module:info {module}';

    protected $description = 'Display detailed information about a specific module';

    public function handle(Filesystem $files)
    {
        $module = $this->argument('module');
        $modulePath = base_path("Modules/{$module}/module.json");

        if (! $files->exists($modulePath)) {
            $this->error("Module not found: {$module}");

            return Command::FAILURE;
        }

        $moduleConfig = json_decode($files->get($modulePath), true);
        $this->info('Module Information:');
        $this->line(json_encode($moduleConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return Command::SUCCESS;
    }
}
