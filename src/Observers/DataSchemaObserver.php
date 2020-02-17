<?php

namespace Amethyst\Observers;

use Amethyst\DataSchema\Helper;
use Amethyst\DataSchema\Manager;
use Amethyst\Models\DataSchema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataSchemaObserver
{
    /**
     * Handle the DataSchema "created" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function created(DataSchema $dataSchema)
    {
        Schema::create(Helper::toTable($dataSchema->name), function (Blueprint $table) use ($dataSchema) {
            $table->increments('id');
            $table->timestamps();
        });

        $manager = new Manager(null, false);
        $manager->reloadByDataSchema($dataSchema);

        app('amethyst')->addData($dataSchema->name, $manager);

        $this->reload($dataSchema);
    }

    /**
     * Handle the DataSchema "updated" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function updated(DataSchema $dataSchema)
    {
        $oldName = $dataSchema->getOriginal()['name'];

        if ($dataSchema->name !== $oldName) {
            Schema::rename(Helper::toTable($oldName), Helper::toTable($dataSchema->name));

            $manager = new Manager(null, false);
            $manager->reloadByDataSchema($dataSchema);

            app('amethyst')->removeData($oldName);
            app('amethyst')->addData($dataSchema->name, $manager);
        }

        $this->reload($dataSchema);
    }

    /**
     * Handle the DataSchema "deleted" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function deleted(DataSchema $dataSchema)
    {
        Schema::drop(Helper::toTable($dataSchema->name));

        app('amethyst')->removeData($dataSchema->name);

        $this->reload($dataSchema);
    }

    public function reload(DataSchema $dataSchema)
    {
        event(new \Railken\EloquentMapper\Events\EloquentMapUpdate(''));
    }
}
