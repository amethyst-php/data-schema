<?php

namespace Amethyst\DataSchema;

use Amethyst\DataSchema\Model as DataSchemaModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Imanghafoori\Relativity\RelationStore as RelationStoreBase;

class RelationStore extends RelationStoreBase
{
    /**
     * Retrieve all relations.
     *
     * @param Model $model
     *
     * @return array
     */
    public function all(Model $model)
    {
        if (!($model instanceof DataSchemaModel)) {
            return parent::all($model);
        }

        return Arr::get($this->relations, $model->getMorphClass());
    }

    /**
     * Retrieve key.
     *
     * @param Model  $model
     * @param string $key
     *
     * @return string
     */
    public function getKey(Model $model, $key)
    {
        if (!($model instanceof DataSchemaModel)) {
            return parent::getKey($model, $key);
        }

        return $model->getMorphClass().'.'.$key;
    }
}
