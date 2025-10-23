<?php

use Illuminate\Filesystem\Filesystem;

it('enables a module', function () {
    $filesystem = new Filesystem;
    $modulePath = base_path('Modules/TestModule/module.json');

    // Create a mock module.json file
    $filesystem->ensureDirectoryExists(base_path('Modules/TestModule'));
    $filesystem->put($modulePath, json_encode(['enabled' => false], JSON_PRETTY_PRINT));

    // Run the command
    $this->artisan('module:enable', ['module' => 'TestModule'])
        ->expectsOutput('Module enabled: TestModule')
        ->assertExitCode(0);

    // Assert the module.json file is updated
    $moduleConfig = json_decode($filesystem->get($modulePath), true);
    expect($moduleConfig['enabled'])->toBeTrue();

    // Cleanup
    $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
