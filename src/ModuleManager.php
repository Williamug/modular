<?php

namespace Williamug\Modular;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ModuleManager
{
  protected string $modulesPath;
  protected array $modules = [];

  public function __construct(?string $modulesPath = null)
  {
    $this->modulesPath = $modulesPath ?: base_path('modules');
  }

  /**
   * Scan modules directory and register enabled modules.
   */
  public function scanAndRegister(): array
  {
    $found = [];

    if (!is_dir($this->modulesPath)) {
      return $found;
    }

    foreach (glob($this->modulesPath . '/*', GLOB_ONLYDIR) as $dir) {
      $slug = basename($dir);
      $manifest = $dir . '/module.json';

      if (!File::exists($manifest)) {
        continue;
      }

      $content = json_decode(File::get($manifest), true);
      if (!$content || empty($content['name'])) {
        continue;
      }

      $content['__path'] = $dir;
      $this->modules[$slug] = $content;
      $found[$slug] = $content;

      // Only proceed if module is enabled (default true)
      $enabled = $content['enabled'] ?? true;
      if (!$enabled) {
        continue;
      }

      // Register the module's service provider (if any)
      $this->registerProvider($content, $dir);

      // Load module hooks if hooks.php exists
      $this->registerHooks($dir);
    }

    return $found;
  }

  /**
   * Return discovered modules.
   */
  public function all(): array
  {
    return $this->modules;
  }

  /**
   * Register a module's provider if present.
   */
  protected function registerProvider(array $content, string $dir): void
  {
    if (empty($content['provider'])) {
      return;
    }

    $provider = $content['provider'];

    if (class_exists($provider)) {
      app()->register($provider);
      return;
    }

    // Fallback: manually include file if not autoloaded
    $possible = $dir . '/Providers/' . class_basename($provider) . '.php';
    if (file_exists($possible)) {
      require_once $possible;
      if (class_exists($provider)) {
        app()->register($provider);
      }
    }
  }

  /**
   * Automatically register hooks from a module's hooks.php file.
   */
  protected function registerHooks(string $dir): void
  {
    $hooksFile = $dir . '/hooks.php';
    if (!file_exists($hooksFile)) {
      return;
    }

    try {
      $hookManager = app(HookManager::class);
      $hooks = include $hooksFile;

      if (is_array($hooks)) {
        foreach ($hooks as $hook => $callback) {
          if (is_callable($callback)) {
            $hookManager->listen($hook, $callback);
          }
        }
      } elseif (is_callable($hooks)) {
        // Single function returning multiple hook registrations
        $hooks($hookManager);
      }
    } catch (\Throwable $e) {
      Log::error("Failed to register hooks for module at {$dir}: " . $e->getMessage());
    }
  }
}
