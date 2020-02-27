<?php

namespace Amethyst\Providers;

use Amethyst\Core\Providers\CommonServiceProvider;
use Amethyst\Models\DataSchema;
use Amethyst\Observers\DataSchemaObserver;

class DataSchemaServiceProvider extends CommonServiceProvider
{
    /**
     * @inherit
     */
    public function register()
    {
        parent::register();

        $this->app->register(\Amethyst\Providers\AttributeSchemaServiceProvider::class);
        $this->app->register(\Amethyst\Providers\RelationSchemaServiceProvider::class);

        $this->app->singleton('amethyst.data-schema', function ($app) {
            return new \Amethyst\Services\DataSchema();
        });
    }

    /**
     * @inherit
     */
    public function boot()
    {
        parent::boot();

        DataSchema::observe(DataSchemaObserver::class);
    }
}
