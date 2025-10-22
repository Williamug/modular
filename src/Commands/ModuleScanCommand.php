<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Williamug\Modular\ModuleManager;

class ModuleScanCommand extends Command
{
  protected $signature = 'module:scan';
  protected $description = 'Scan modules folder and register providers';

  public function handle(ModuleManager $manager)
  {
    $modules = $manager->scanAndRegister();
    $this->info('Modules scanned: ' . count($modules));
    return 0;
  }
}
