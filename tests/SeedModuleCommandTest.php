<?php

use Illuminate\Database\Seeder;

it('seeds data for a module', function () {
    $seeder = mock(Seeder::class);
    $module = 'TestModule';
    $seederClass = "Modules\\{$module}\\Database\\Seeders\\{$module}Seeder";

    // Dynamically create the mock seeder class
    eval("namespace Modules\\{$module}\\Database\\Seeders; class {$module}Seeder {}");

    // Mock the seeder
    $seeder->shouldReceive('call')->with($seederClass)->once();
    $this->app->instance(Seeder::class, $seeder);

    // Run the command
    $this->artisan('module:seed', ['module' => $module])
        ->expectsOutput("Seeded data for module: {$module}")
        ->assertExitCode(0);
});
