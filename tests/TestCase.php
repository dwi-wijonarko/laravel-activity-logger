<?php

namespace DwiWijonarko\ActivityLogger\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use DwiWijonarko\ActivityLogger\ActivityLoggerServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ActivityLoggerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
