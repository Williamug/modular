<?php

namespace Williamug\Modular;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Williamug\Modular\Commands\ModularCommand;

class ModularServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('modular')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_modular_table')
            ->hasCommand(ModularCommand::class);
    }
}
