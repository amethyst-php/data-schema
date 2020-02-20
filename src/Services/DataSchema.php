<?php

namespace Amethyst\Services;

use Amethyst\DataSchema\Manager;
use Amethyst\Models;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class DataSchema
{
    protected $attributes;

    public function boot()
    {
        if (!Schema::hasTable(Config::get('amethyst.data-schema.data.data-schema.table'))) {
            return;
        }

        foreach (Models\DataSchema::all() as $data) {
            $manager = new Manager(null, false);
            $manager->reloadByDataSchema($data);

            app('amethyst')->addData($data->name, $manager);
            app('amethyst')->bootData($manager);
        }
    }
}
