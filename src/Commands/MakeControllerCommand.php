<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeControllerCommand extends Command
{
  protected $signature = 'module:controller {module} {name}';

  protected $description = 'Create a controller within a specific module';

  public function handle(Filesystem $files)
  {
    $module = $this->argument('module');
    $name = Str::studly($this->argument('name'));
    $controllerPath = base_path("Modules/{$module}/app/Http/Controllers/{$name}.php");

    if ($files->exists($controllerPath)) {
      $this->error("Controller already exists: {$name}");

      return Command::FAILURE;
    }

    $stub = file_get_contents(__DIR__ . '/../stubs/controller.stub');
    $content = str_replace('{{module}}', $module, $stub);
    $files->put($controllerPath, $content);

    $this->info("Controller created: {$name}");

    return Command::SUCCESS;
  }
}
