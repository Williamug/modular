<?php

namespace Williamug\Modular;

use Illuminate\Support\Facades\Event;

class HookManager
{
  /**
   * All registered hooks.
   *
   * @var array<string, callable[]>
   */
  protected array $hooks = [];

  /**
   * Listen for a specific hook or event.
   *
   * @param string   $hook
   * @param callable $callback
   */
  public function listen(string $hook, callable $callback): void
  {
    $this->hooks[$hook][] = $callback;
  }

  /**
   * Dispatch a hook/event.
   * Executes all registered callbacks and also dispatches a Laravel event.
   *
   * @param string $hook
   * @param mixed  ...$args
   * @return array<int, mixed> Listener results
   */
  public function dispatch(string $hook, ...$args): array
  {
    $results = [];

    // 1️⃣ Run internal callbacks
    foreach ($this->hooks[$hook] ?? [] as $callback) {
      $results[] = call_user_func_array($callback, $args);
    }

    // 2️⃣ Also dispatch as Laravel events (observable via Event::listen)
    if (class_exists(Event::class)) {
      Event::dispatch("lejasuite.hook.{$hook}", $args);
      Event::dispatch('lejasuite.hook.triggered', [
        'hook' => $hook,
        'args' => $args,
      ]);
    }

    return $results;
  }

  /**
   * Filter a value through all listeners.
   * Each listener can modify and return the value.
   *
   * @param string $hook
   * @param mixed  $value
   * @param mixed  ...$args
   * @return mixed Filtered value
   */
  public function filter(string $hook, mixed $value, ...$args): mixed
  {
    $filtered = $value;

    foreach ($this->hooks[$hook] ?? [] as $callback) {
      $filtered = call_user_func_array($callback, array_merge([$filtered], $args));
    }

    if (class_exists(Event::class)) {
      Event::dispatch("lejasuite.filter.{$hook}", [$filtered, ...$args]);
    }

    return $filtered;
  }

  /**
   * Check if a hook has any listeners.
   */
  public function has(string $hook): bool
  {
    return !empty($this->hooks[$hook]);
  }
}
