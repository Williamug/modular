<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeMigrationCommand extends Command
{
  protected $signature = 'module:migration {module} {name}';

  protected $description = 'Create a migration within a specific module';

  public function handle(Filesystem $files)
  {
    $module = $this->argument('module');
    $name = Str::snake($this->argument('name'));
    $timestamp = date('Y_m_d_His');
    $migrationPath = base_path("Modules/{$module}/database/migrations/{$timestamp}_{$name}.php");

    if ($files->exists($migrationPath)) {
      $this->error("Migration already exists: {$name}");

      return Command::FAILURE;
    }

    $stub = file_get_contents(__DIR__ . '/../stubs/migration.stub');
    $content = str_replace('{{table}}', Str::plural($name), $stub);
    $files->put($migrationPath, $content);

    $this->info("Migration created: {$name}");

    return Command::SUCCESS;
  }
}
