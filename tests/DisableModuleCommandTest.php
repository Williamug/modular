<?php

use Illuminate\Filesystem\Filesystem;

it('disables a module', function () {
  $filesystem = new Filesystem();
  $modulePath = base_path('Modules/TestModule/module.json');

  // Create a mock module.json file
  $filesystem->ensureDirectoryExists(base_path('Modules/TestModule'));
  $filesystem->put($modulePath, json_encode(['enabled' => true], JSON_PRETTY_PRINT));

  // Run the command
  $this->artisan('module:disable', ['module' => 'TestModule'])
    ->expectsOutput('Module disabled: TestModule')
    ->assertExitCode(0);

  // Assert the module.json file is updated
  $moduleConfig = json_decode($filesystem->get($modulePath), true);
  expect($moduleConfig['enabled'])->toBeFalse();

  // Cleanup
  $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
