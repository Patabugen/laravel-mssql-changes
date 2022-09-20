<?php

namespace Patabugen\MssqlChanges\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Patabugen\MssqlChanges\MssqlChanges
 */
class MssqlChanges extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Patabugen\MssqlChanges\MssqlChanges::class;
    }
}
