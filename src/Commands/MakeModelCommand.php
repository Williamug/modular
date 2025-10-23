<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeModelCommand extends Command
{
    protected $signature = 'module:model {module} {name} {--migration}';

    protected $description = 'Create a model within a specific module, with an optional migration';

    public function handle(Filesystem $files)
    {
        $module = $this->argument('module');
        $name = Str::studly($this->argument('name'));
        $modelPath = base_path("Modules/{$module}/Models/{$name}.php");

        if ($files->exists($modelPath)) {
            $this->error("Model already exists: {$name}");

            return Command::FAILURE;
        }

        $modelContent = "<?php\n\nnamespace Modules\\{$module}\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass {$name} extends Model\n{\n    protected \$guarded = [];\n}";
        $files->put($modelPath, $modelContent);

        $this->info("Model created: {$name}");

        if ($this->option('migration')) {
            $this->call('module:migration', [
                'module' => $module,
                'name' => 'create_'.Str::snake(Str::plural($name)).'_table',
            ]);
        }

        return Command::SUCCESS;
    }
}
