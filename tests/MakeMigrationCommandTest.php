<?php

use Illuminate\Filesystem\Filesystem;

it('creates a migration within a module', function () {
  $filesystem = new Filesystem();
  $migrationPath = base_path('Modules/TestModule/Database/migrations');

  // Ensure the module directory exists
  $filesystem->ensureDirectoryExists($migrationPath);

  // Run the command
  $this->artisan('module:migration', ['module' => 'TestModule', 'name' => 'create_test_table'])
    ->expectsOutput('Migration created: create_test_table')
    ->assertExitCode(0);

  // Assert a migration file is created
  $migrationFiles = $filesystem->files($migrationPath);
  expect(count($migrationFiles))->toBeGreaterThan(0);

  // Cleanup
  $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
