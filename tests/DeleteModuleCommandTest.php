<?php

use Illuminate\Filesystem\Filesystem;

it('deletes a module', function () {
  $filesystem = new Filesystem();
  $modulePath = base_path('Modules/TestModule');

  // Create a mock module directory
  $filesystem->ensureDirectoryExists($modulePath);
  $filesystem->put("{$modulePath}/module.json", json_encode(['enabled' => true], JSON_PRETTY_PRINT));

  // Run the command
  $this->artisan('module:delete', ['module' => 'TestModule'])
    ->expectsOutput('Module deleted: TestModule')
    ->assertExitCode(0);

  // Assert the module directory is deleted
  expect($filesystem->isDirectory($modulePath))->toBeFalse();
});
