<?php

namespace Williamug\Modular;

use Illuminate\Support\ServiceProvider;

class ModularCoreServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/config/modules.php', 'modules');

    $this->app->singleton(ModuleManager::class, function ($app) {
      return new ModuleManager(config('modules.modules_path', base_path('modules')));
    });

    $this->app->singleton(HookManager::class, function ($app) {
      return new HookManager();
    });

    // register commands
    $this->commands([
      Commands\MakeModuleCommand::class,
      Commands\ListModulesCommand::class,
      Commands\ModuleScanCommand::class,
    ]);
  }

  public function boot(ModuleManager $manager)
  {
    if (config('modules.auto_scan', true)) {
      $manager->scanAndRegister();
    }
  }
}
