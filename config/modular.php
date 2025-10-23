<?php

// config for Williamug/Modular
return [
  /**
   * Path to modules
   */
  'modules_path' => env('MODULAR_PATH', base_path('Modules')),

  /**
   * Whether to auto-scan modules on boot (set false for manual control)
   */
  'auto_scan' => true,
];
