<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migrator;

class MigrateModuleCommand extends Command
{
    protected $signature = 'module:migrate {module}';

    protected $description = 'Run migrations for a specific module';

    public function handle(Migrator $migrator)
    {
        $module = $this->argument('module');
        $migrationsPath = base_path("Modules/{$module}/Database/migrations");

        if (! is_dir($migrationsPath)) {
            $this->error("No migrations found for module: {$module}");

            return Command::FAILURE;
        }

        $migrator->run($migrationsPath);
        $this->info("Migrations run for module: {$module}");

        return Command::SUCCESS;
    }
}
