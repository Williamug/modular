<?php

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

it('runs migrations for a module', function () {
    $filesystem = new Filesystem;
    $modulesPath = base_path('Modules');
    $modulePath = $modulesPath.'/TestModule/Database/migrations';

    // Ensure the Modules and migrations directories exist
    $filesystem->ensureDirectoryExists($modulePath);
    $filesystem->put("{$modulePath}/2025_10_23_000000_create_test_table.php", '<?php // Migration file');

    $migrator = mock(Migrator::class);

    // Mock the migrator
    $migrator->shouldReceive('run')->with($modulePath)->once();
    $this->app->instance(Migrator::class, $migrator);

    // Run the command
    $this->artisan('module:migrate', ['module' => 'TestModule'])
        ->expectsOutput('Migrations run for module: TestModule')
        ->assertExitCode(0);

    // Cleanup
    $filesystem->deleteDirectory($modulesPath);
});
