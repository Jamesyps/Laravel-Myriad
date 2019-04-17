<?php

namespace Jamesyps\Myriad\Tests;

use Jamesyps\Myriad\Facades\Myriad;
use Jamesyps\Myriad\MyriadServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class MyriadTestCase extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            MyriadServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Myriad' => Myriad::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('myriad.directory', __DIR__ . '/components');
    }

}
