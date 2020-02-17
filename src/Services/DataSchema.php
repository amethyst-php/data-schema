<?php

namespace Amethyst\Services;

use Amethyst\Models;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class DataSchema
{
    protected $attributes;

    public function reload()
    {
        $this->data = Models\DataSchema::all();
    }

    public function boot()
    {
        if (!Schema::hasTable(Config::get('amethyst.data-schema.data.data-schema.table'))) {
            return;
        }
    }
}
