<?php

namespace YasserElgammal\LaravelEgyptNationalIdParser\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use YasserElgammal\LaravelEgyptNationalIdParser\NationalIdServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            NationalIdServiceProvider::class,
        ];
    }
}
