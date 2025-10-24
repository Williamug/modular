<?php

namespace Williamug\Modular\Tests;

class ModuleManagerTest extends TestCase
{
    public function test_scans_and_registers_valid_modules()
    {
        $manager = app(\Williamug\Modular\ModuleManager::class);

        // Mock the modules directory
        $modulesPath = base_path('Modules');
        if (is_dir($modulesPath)) {
            $this->recursiveDelete($modulesPath);
        }
        mkdir($modulesPath, 0755, true);

        $testModulePath = "{$modulesPath}/TestModule";
        mkdir($testModulePath, 0755, true);

        $moduleJsonPath = "{$testModulePath}/module.json";
        $moduleJsonContent = json_encode([
            'name' => 'TestModule',
            'slug' => 'test-module',
            'enabled' => true,
        ]);
        file_put_contents($moduleJsonPath, $moduleJsonContent);

        // Debugging output removed

        // Ensure the module manager scans and registers the module
        $modules = $manager->scanAndRegister();

        // Adjust expectations to match the actual structure
        expect($modules)->toHaveKey('test-module');
        expect($modules['test-module']['name'])->toBe('TestModule');

        // Cleanup
        unlink($moduleJsonPath);
        rmdir($testModulePath);
        rmdir($modulesPath);
    }

    public function test_handles_invalid_module_json_gracefully()
    {
        $manager = app(\Williamug\Modular\ModuleManager::class);

        // Mock the modules directory
        $modulesPath = base_path('Modules');
        if (! is_dir($modulesPath)) {
            mkdir($modulesPath);
        }
        $invalidModulePath = "{$modulesPath}/InvalidModule";
        if (! is_dir($invalidModulePath)) {
            mkdir($invalidModulePath);
        }
        file_put_contents("{$invalidModulePath}/module.json", 'invalid-json');

        $modules = $manager->scanAndRegister();

        expect($modules)->not->toHaveKey('invalid-module');

        // Cleanup
        unlink("{$invalidModulePath}/module.json");
        rmdir($invalidModulePath);

        // Recursive cleanup for Modules directory
        $this->recursiveDelete($modulesPath);
    }

    private function recursiveDelete(string $directory): void
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                $this->recursiveDelete($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
