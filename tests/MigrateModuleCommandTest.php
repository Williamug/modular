<?php

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

it('runs migrations for a module', function () {
  $filesystem = new Filesystem();
  $migrator = mock(Migrator::class);
  $modulePath = base_path('Modules/TestModule/Database/migrations');

  // Create a mock migrations directory
  $filesystem->ensureDirectoryExists($modulePath);
  $filesystem->put("{$modulePath}/2025_10_23_000000_create_test_table.php", '<?php ');

  // Mock the migrator
  $migrator->shouldReceive('run')->with($modulePath)->once();
  $this->app->instance(Migrator::class, $migrator);

  // Run the command
  $this->artisan('module:migrate', ['module' => 'TestModule'])
    ->expectsOutput('Migrations run for module: TestModule')
    ->assertExitCode(0);

  // Cleanup
  $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
