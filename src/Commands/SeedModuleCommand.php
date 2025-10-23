<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Seeder;

class SeedModuleCommand extends Command
{
  protected $signature = 'module:seed {module}';
  protected $description = 'Seed data for a specific module';

  public function handle()
  {
    $module = $this->argument('module');
    $seederClass = "Modules\\{$module}\\Database\\Seeders\\{$module}Seeder";

    if (!class_exists($seederClass)) {
      $this->error("Seeder class not found: {$seederClass}");
      return Command::FAILURE;
    }

    app(Seeder::class)->call($seederClass);
    $this->info("Seeded data for module: {$module}");
    return Command::SUCCESS;
  }
}
