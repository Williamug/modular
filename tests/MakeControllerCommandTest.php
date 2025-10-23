<?php

use Illuminate\Filesystem\Filesystem;

it('creates a controller within a module', function () {
    $filesystem = new Filesystem;
    $controllerPath = base_path('Modules/TestModule/Http/Controllers/TestController.php');

    // Ensure the module directory exists
    $filesystem->ensureDirectoryExists(base_path('Modules/TestModule/Http/Controllers'));

    // Run the command
    $this->artisan('module:controller', ['module' => 'TestModule', 'name' => 'TestController'])
        ->expectsOutput('Controller created: TestController')
        ->assertExitCode(0);

    // Assert the controller file is created
    expect($filesystem->exists($controllerPath))->toBeTrue();

    // Cleanup
    $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
