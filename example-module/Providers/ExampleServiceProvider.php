<?php

namespace Modules\Example\Providers;

use Illuminate\Support\ServiceProvider;
use LejaSuite\ModularCore\HookManager;

class ExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register an example hook handler at boot
        if (class_exists(HookManager::class)) {
            app(HookManager::class)->add('example.hook', function ($payload) {
                \Log::info('Example module hook triggered', (array) $payload);
                return ['example' => 'handled'];
            });
        }
    }
}
