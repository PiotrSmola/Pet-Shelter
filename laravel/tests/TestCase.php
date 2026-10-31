<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        // RefreshDatabase wipes the database, so never run against anything but a *_test one.
        $database = $app['config']->get('database.connections.' . $app['config']->get('database.default') . '.database');

        if (! str_ends_with((string) $database, '_test')) {
            throw new RuntimeException("Refusing to run tests against database [{$database}].");
        }

        return $app;
    }
}
