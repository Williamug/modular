<?php

namespace Williamug\Modular;

use Illuminate\Support\ServiceProvider;
use Williamug\Modular\Commands\DeleteModuleCommand;
use Williamug\Modular\Commands\DisableModuleCommand;
use Williamug\Modular\Commands\EnableModuleCommand;
use Williamug\Modular\Commands\InfoModuleCommand;
use Williamug\Modular\Commands\ListModulesCommand;
use Williamug\Modular\Commands\MakeControllerCommand;
use Williamug\Modular\Commands\MakeMigrationCommand;
use Williamug\Modular\Commands\MakeModelCommand;
use Williamug\Modular\Commands\MakeModuleCommand;
use Williamug\Modular\Commands\MigrateModuleCommand;
use Williamug\Modular\Commands\ModularInstallCommand;
use Williamug\Modular\Commands\ModuleScanCommand;
use Williamug\Modular\Commands\PublishModuleCommand;
use Williamug\Modular\Commands\SeedModuleCommand;
use Illuminate\Support\Facades\Blade;

class ModularServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/config/modular.php', 'modular');

    $this->app->singleton(ModuleManager::class, function () {
      return new ModuleManager(base_path('Modules'));
    });

    $this->app->singleton(HookManager::class, function () {
      return new HookManager;
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

    // Register modularNavigation Blade directive
    Blade::directive('modularNavigation', function () {
      return '<?php $groups = []; foreach (modular_navigation() as $item): if ($item["permission"] && !auth()->user()?->can($item["permission"])) continue; $groups[$item["group"] ?? "Other"][] = $item; endforeach; ?>'
        . '<?php foreach ($groups as $group => $items): ?>'
        . '<li class="nav-group"><span><?= $group ?></span><ul>'
        . '<?php foreach ($items as $item): ?>'
        . '<li><a href="<?= $item["url"] ?>">'
        . '<?php if ($item["icon"]): ?><i class="<?= $item["icon"] ?>"></i><?php endif; ?>'
        . '<?= $item["label"] ?></a></li>'
        . '<?php endforeach; ?></ul></li>'
        . '<?php endforeach; ?>';
    });

    // Register modularSettings Blade directive
    Blade::directive('modularSettings', function () {
      return '<?php $groups = []; foreach (modular_settings() as $item): if ($item["permission"] && !auth()->user()?->can($item["permission"])) continue; $groups[$item["group"] ?? "Other"][] = $item; endforeach; ?>'
        . '<?php foreach ($groups as $group => $items): ?>'
        . '<div class="settings-group"><h3><?= $group ?></h3>'
        . '<?php foreach ($items as $item): ?>'
        . '<?php if ($item["icon"]): ?><i class="<?= $item["icon"] ?>"></i><?php endif; ?>'
        . '<?php if ($item["view"]): ?><?php echo view($item["view"])->render(); ?><?php endif; ?>'
        . '<?php endforeach; ?></div>'
        . '<?php endforeach; ?>';
    });

    // Register modularWidgets Blade directive
    Blade::directive('modularWidgets', function () {
      return '<?php $groups = []; foreach (modular_widgets() as $item): if ($item["permission"] && !auth()->user()?->can($item["permission"])) continue; $groups[$item["group"] ?? "Other"][] = $item; endforeach; ?>'
        . '<?php foreach ($groups as $group => $items): ?>'
        . '<div class="widget-group"><h3><?= $group ?></h3>'
        . '<?php foreach ($items as $item): ?>'
        . '<?php if ($item["icon"]): ?><i class="<?= $item["icon"] ?>"></i><?php endif; ?>'
        . '<?php if ($item["view"]): ?><?php echo view($item["view"])->render(); ?><?php endif; ?>'
        . '<?php endforeach; ?></div>'
        . '<?php endforeach; ?>';
    });

    // Register modularContent Blade directive
    Blade::directive('modularContent', function () {
      return '<?php $groups = []; foreach (modular_content() as $item): if ($item["permission"] && !auth()->user()?->can($item["permission"])) continue; $groups[$item["group"] ?? "Other"][] = $item; endforeach; ?>'
        . '<?php foreach ($groups as $group => $items): ?>'
        . '<div class="content-group"><h3><?= $group ?></h3>'
        . '<?php foreach ($items as $item): ?>'
        . '<?php if ($item["icon"]): ?><i class="<?= $item["icon"] ?>"></i><?php endif; ?>'
        . '<?php if ($item["view"]): ?><?php echo view($item["view"])->render(); ?><?php endif; ?>'
        . '<?php endforeach; ?></div>'
        . '<?php endforeach; ?>';
    });
  }
}
