<?php

namespace Williamug\Modular;

use Illuminate\Support\ServiceProvider;
use Williamug\Modular\Commands\ListModulesCommand;
use Williamug\Modular\Commands\MakeModuleCommand;
use Williamug\Modular\Commands\ModuleScanCommand;

class ModularServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/config/modular.php', 'modular');

    $this->app->singleton(ModuleManager::class, function ($app) {
      return new ModuleManager(config('modular.modules_path', base_path('modules')));
    });

    $this->app->singleton(HookManager::class, function ($app) {
      return new HookManager();
    });

    // register commands
    $this->commands([
      MakeModuleCommand::class,
      ListModulesCommand::class,
      ModuleScanCommand::class,
    ]);
  }

  public function boot(ModuleManager $manager)
  {
    if (config('modules.auto_scan', true)) {
      $manager->scanAndRegister();
    }
  }
}
