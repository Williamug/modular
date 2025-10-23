<?php

namespace Williamug\Modular\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Williamug\Modular\Modular
 */
class Modular extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Williamug\Modular\ModuleManager::class;
    }
}
