<?php

namespace Amethyst\Providers;

use Amethyst\Core\Providers\CommonServiceProvider;
use Amethyst\Observers\DataSchemaObserver;
use Amethyst\Models\DataSchema;

class DataSchemaServiceProvider extends CommonServiceProvider
{
    /**
     * @inherit
     */
    public function boot()
    {
        parent::boot();

        DataSchema::observe(DataSchemaObserver::class);
    }
}
