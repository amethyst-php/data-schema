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
    public function boot()
    {
        parent::boot();

        DataSchema::observe(DataSchemaObserver::class);
    }
}
