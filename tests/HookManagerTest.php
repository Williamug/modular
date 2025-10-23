<?php

test('it listens and dispatches hooks', function () {
  $hookManager = app(\Williamug\Modular\HookManager::class);

  $hookManager->listen('test.hook', function ($payload) {
    return "Handled: {$payload}";
  });

  $results = $hookManager->dispatch('test.hook', 'payload');

  expect($results)->toHaveCount(1);
  expect($results[0])->toBe('Handled: payload');
});

test('it filters values through hooks', function () {
  $hookManager = app(\Williamug\Modular\HookManager::class);

  $hookManager->listen('test.filter', function ($value) {
    return $value . ' filtered';
  });

  $result = $hookManager->filter('test.filter', 'value');

  expect($result)->toBe('value filtered');
});
