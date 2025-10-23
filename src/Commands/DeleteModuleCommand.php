<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DeleteModuleCommand extends Command
{
    protected $signature = 'module:delete {module}';

    protected $description = 'Delete a specific module';

    public function handle(Filesystem $files)
    {
        $module = $this->argument('module');
        $modulePath = base_path("Modules/{$module}");

        if (! $files->isDirectory($modulePath)) {
            $this->error("Module not found: {$module}");

            return Command::FAILURE;
        }

        $files->deleteDirectory($modulePath);
        $this->info("Module deleted: {$module}");

        return Command::SUCCESS;
    }
}
