<?php

namespace Williamug\Modular\Commands;

use Illuminate\Console\Command;
use Williamug\Modular\ModuleManager;

class ListModulesCommand extends Command
{
  protected $signature = 'module:list';
  protected $description = 'List discovered modules';

  public function handle(ModuleManager $manager)
  {
    $modules = $manager->scanAndRegister();
    if (empty($modules)) {
      $this->info('No modules found.');
      return 0;
    }
    foreach ($modules as $slug => $m) {
      $this->line(sprintf('%-20s %s', $slug, $m['name'] ?? ''));
    }
    return 0;
  }
}
