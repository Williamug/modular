<?php

if (! function_exists('module_path')) {
    function module_path(string $slug = '')
    {
        return base_path('modules'.($slug ? DIRECTORY_SEPARATOR.$slug : ''));
    }
}
