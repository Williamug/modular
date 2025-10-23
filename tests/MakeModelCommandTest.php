<?php

use Illuminate\Filesystem\Filesystem;

it('creates a model within a module', function () {
    $filesystem = new Filesystem;
    $modelPath = base_path('Modules/TestModule/Models/TestModel.php');

    // Ensure the module directory exists
    $filesystem->ensureDirectoryExists(base_path('Modules/TestModule/Models'));

    // Run the command
    $this->artisan('module:model', ['module' => 'TestModule', 'name' => 'TestModel'])
        ->expectsOutput('Model created: TestModel')
        ->assertExitCode(0);

    // Assert the model file is created
    expect($filesystem->exists($modelPath))->toBeTrue();

    // Cleanup
    $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});

it('creates a model with a migration within a module', function () {
    $filesystem = new Filesystem;
    $modelPath = base_path('Modules/TestModule/Models/TestModel.php');
    $migrationPath = base_path('Modules/TestModule/Database/migrations');

    // Ensure the module directory exists
    $filesystem->ensureDirectoryExists(base_path('Modules/TestModule/Models'));
    $filesystem->ensureDirectoryExists($migrationPath);

    // Run the command with --migration
    $this->artisan('module:model', ['module' => 'TestModule', 'name' => 'TestModel', '--migration' => true])
        ->expectsOutput('Model created: TestModel')
        ->assertExitCode(0);

    // Assert the model file is created
    expect($filesystem->exists($modelPath))->toBeTrue();

    // Assert a migration file is created
    $migrationFiles = $filesystem->files($migrationPath);
    expect(count($migrationFiles))->toBeGreaterThan(0);

    // Cleanup
    $filesystem->deleteDirectory(base_path('Modules/TestModule'));
});
