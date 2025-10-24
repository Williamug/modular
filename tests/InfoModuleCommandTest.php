<?php

use Illuminate\Filesystem\Filesystem;

it('displays information about a module', function () {
    $filesystem = new Filesystem;
    $modulePath = base_path('Modules/TestModule/module.json');

    // Create a mock module.json file
    $filesystem->ensureDirectoryExists(base_path('Modules/TestModule'));
    $filesystem->put($modulePath, json_encode(['name' => 'TestModule', 'enabled' => true], JSON_PRETTY_PRINT));

    // Run the command
    $this->artisan('module:info', ['module' => 'TestModule'])
        ->expectsOutput('Module Information:')
        ->expectsOutput(json_encode(['name' => 'TestModule', 'enabled' => true], JSON_PRETTY_PRINT))
        ->assertExitCode(0);

    // Cleanup
    $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
