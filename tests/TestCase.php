<?php

namespace Williamug\Modular\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Williamug\Modular\ModularServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Explicitly load the configuration file from the new location
        $this->app['config']->set('modular', require __DIR__.'/../src/config/modular.php');

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Williamug\\Modular\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ModularServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
             foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
                (include $migration->getRealPath())->up();
             }
             */
    }
}
