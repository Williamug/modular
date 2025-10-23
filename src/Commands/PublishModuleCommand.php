<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishModuleCommand extends Command
{
    protected $signature = 'module:publish {module}';

    protected $description = 'Publish assets or configuration for a specific module';

    public function handle(Filesystem $files)
    {
        $module = $this->argument('module');
        $assetsPath = base_path("Modules/{$module}/resources/assets");
        $publishPath = public_path("modules/{$module}");

        if (! $files->isDirectory($assetsPath)) {
            $this->error("No assets found for module: {$module}");

            return Command::FAILURE;
        }

        $files->copyDirectory($assetsPath, $publishPath);
        $this->info("Assets published for module: {$module}");

        return Command::SUCCESS;
    }
}
