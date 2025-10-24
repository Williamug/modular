<?php

if (! function_exists('module_path')) {
  function module_path(string $slug = '')
  {
    return base_path('Modules' . ($slug ? DIRECTORY_SEPARATOR . $slug : ''));
  }
}

/**
 * Collect navigation items from all enabled modules.
 * Each module can register navigation items via hooks or config.
 * Returns an array of ['label' => ..., 'url' => ..., 'icon' => ..., 'group' => ..., 'permission' => ...] items.
 */
function modular_navigation(): array
{
  $items = [];
  $manager = app(\Williamug\Modular\ModuleManager::class);
  $modules = $manager->all();
  foreach ($modules as $slug => $module) {
    // Convention: each module can define navigation in 'navigation' key in module.json
    if (!empty($module['navigation']) && is_array($module['navigation'])) {
      foreach ($module['navigation'] as $nav) {
        // Default values for advanced features
        $nav['icon'] = $nav['icon'] ?? null;
        $nav['group'] = $nav['group'] ?? null;
        $nav['permission'] = $nav['permission'] ?? null;
        $items[] = $nav;
      }
    }
  }
  return $items;
}

/**
 * Collect settings items from all enabled modules.
 * Each module can register settings items via the 'settings' key in module.json.
 * Returns an array of ['label' => ..., 'view' => ..., 'icon' => ..., 'group' => ..., 'permission' => ...] items.
 */
function modular_settings(): array
{
  $items = [];
  $manager = app(\Williamug\Modular\ModuleManager::class);
  $modules = $manager->all();
  foreach ($modules as $slug => $module) {
    if (!empty($module['settings']) && is_array($module['settings'])) {
      foreach ($module['settings'] as $setting) {
        $setting['icon'] = $setting['icon'] ?? null;
        $setting['group'] = $setting['group'] ?? null;
        $setting['permission'] = $setting['permission'] ?? null;
        $items[] = $setting;
      }
    }
  }
  return $items;
}

/**
 * Collect dashboard widgets from all enabled modules.
 * Each module can register widgets via the 'widgets' key in module.json.
 * Returns an array of ['label' => ..., 'view' => ..., 'icon' => ..., 'group' => ..., 'permission' => ...] items.
 */
function modular_widgets(): array
{
  $items = [];
  $manager = app(\Williamug\Modular\ModuleManager::class);
  $modules = $manager->all();
  foreach ($modules as $slug => $module) {
    if (!empty($module['widgets']) && is_array($module['widgets'])) {
      foreach ($module['widgets'] as $widget) {
        $widget['icon'] = $widget['icon'] ?? null;
        $widget['group'] = $widget['group'] ?? null;
        $widget['permission'] = $widget['permission'] ?? null;
        $items[] = $widget;
      }
    }
  }
  return $items;
}

/**
 * Collect generic content slots from all enabled modules.
 * Each module can register content via the 'content' key in module.json.
 * Returns an array of ['label' => ..., 'view' => ..., 'icon' => ..., 'group' => ..., 'permission' => ...] items.
 */
function modular_content(): array
{
  $items = [];
  $manager = app(\Williamug\Modular\ModuleManager::class);
  $modules = $manager->all();
  foreach ($modules as $slug => $module) {
    if (!empty($module['content']) && is_array($module['content'])) {
      foreach ($module['content'] as $content) {
        $content['icon'] = $content['icon'] ?? null;
        $content['group'] = $content['group'] ?? null;
        $content['permission'] = $content['permission'] ?? null;
        $items[] = $content;
      }
    }
  }
  return $items;
}
