<?php

namespace Amethyst\Tests;

abstract class BaseTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
        app('amethyst.relation-schema')->boot();
        app('amethyst.attribute-schema')->boot();
        app('eloquent.mapper')->boot();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Amethyst\Providers\DataSchemaServiceProvider::class,
        ];
    }
}
