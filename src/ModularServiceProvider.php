<?php

namespace Williamug\Modular;

use Illuminate\Support\ServiceProvider;
use Williamug\Modular\Commands\ListModulesCommand;
use Williamug\Modular\Commands\MakeModuleCommand;
use Williamug\Modular\Commands\ModuleScanCommand;
use Williamug\Modular\Commands\ModularInstallCommand;
use Williamug\Modular\Commands\EnableModuleCommand;
use Williamug\Modular\Commands\DisableModuleCommand;
use Williamug\Modular\Commands\DeleteModuleCommand;
use Williamug\Modular\Commands\MigrateModuleCommand;
use Williamug\Modular\Commands\SeedModuleCommand;
use Williamug\Modular\Commands\PublishModuleCommand;
use Williamug\Modular\Commands\InfoModuleCommand;
use Williamug\Modular\Commands\MakeControllerCommand;
use Williamug\Modular\Commands\MakeModelCommand;
use Williamug\Modular\Commands\MakeMigrationCommand;

class ModularServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/config/modular.php', 'modular');

    $this->app->singleton(ModuleManager::class, function () {
      return new ModuleManager(base_path('Modules'));
    });

    $this->app->singleton(HookManager::class, function () {
      return new HookManager();
    });

    // register commands
    $this->commands([
      MakeModuleCommand::class,
      ListModulesCommand::class,
      ModuleScanCommand::class,
      ModularInstallCommand::class,
      EnableModuleCommand::class,
      DisableModuleCommand::class,
      DeleteModuleCommand::class,
      MigrateModuleCommand::class,
      SeedModuleCommand::class,
      PublishModuleCommand::class,
      InfoModuleCommand::class,
      MakeControllerCommand::class,
      MakeModelCommand::class,
      MakeMigrationCommand::class,
    ]);
  }

  public function boot(ModuleManager $manager)
  {
    if (config('modules.auto_scan', true)) {
      $manager->scanAndRegister();
    }
  }
}
