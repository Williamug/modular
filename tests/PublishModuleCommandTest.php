<?php

use Illuminate\Filesystem\Filesystem;

it('publishes assets for a module', function () {
  $filesystem = new Filesystem();
  $assetsPath = base_path('Modules/TestModule/resources/assets');
  $publishPath = public_path('modules/TestModule');

  // Create a mock assets directory
  $filesystem->ensureDirectoryExists($assetsPath);
  $filesystem->put("{$assetsPath}/example.css", '/* Example CSS */');

  // Run the command
  $this->artisan('module:publish', ['module' => 'TestModule'])
    ->expectsOutput('Assets published for module: TestModule')
    ->assertExitCode(0);

  // Assert the assets are copied
  expect($filesystem->exists("{$publishPath}/example.css"))->toBeTrue();

  // Cleanup
  $filesystem->deleteDirectory(base_path('Modules/TestModule'));
  $filesystem->deleteDirectory(public_path('modules/TestModule'));
});
