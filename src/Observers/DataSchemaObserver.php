<?php

namespace Amethyst\Observers;

use Amethyst\Models\DataSchema;
use Doctrine\Common\Inflector\Inflector;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataSchemaObserver
{
    const PREFIX = 'ac_';

    protected $inflector;

    public function __construct()
    {
        $this->inflector = new Inflector();
    }

    /**
     * Handle the DataSchema "created" event.
     *
     * @param \Amethyst\Models\DataSchema $dataSchema
     */
    public function created(DataSchema $dataSchema)
    {
        Schema::create($this->toTable(static::PREFIX.$dataSchema->name), function (Blueprint $table) use ($dataSchema) {
            $table->increments('id');
            $table->timestamps();
        });

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
            Schema::rename($this->toTable(static::PREFIX.$oldName), $this->toTable(static::PREFIX.$dataSchema->getSchemaTable()));
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
        Schema::drop($this->toTable(static::PREFIX.$dataSchema->name));

        $this->reload($dataSchema);
    }

    public function reload(DataSchema $dataSchema)
    {
        app('amethyst')->ini();

        event(new \Railken\EloquentMapper\Events\EloquentMapUpdate(''));
    }

    public function toTable(string $name)
    {
        return $this->inflector->tableize(str_replace('-', '_', $name));
    }
}
