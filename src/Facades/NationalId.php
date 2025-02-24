<?php

namespace YasserElgammal\LaravelEgyptNationalIdParser\Facades;

use Illuminate\Support\Facades\Facade;

class NationalId extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */

    protected static function getFacadeAccessor()
    {
        return 'national-id';
    }
}
