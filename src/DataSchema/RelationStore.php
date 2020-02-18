<?php

namespace Amethyst\DataSchema;

use Illuminate\Database\Eloquent\Model;
use Imanghafoori\Relativity\RelationStore as RelationStoreBase;
use Amethyst\DataSchema\Model as DataSchemaModel;

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
        return $this->relations;
    }

    /**
     * Retrieve key
     *
     * @param Model $model
     * @param string $key
     *
     * @return string
     */
    public function getKey(Model $model, $key)
    {
        if (!($model instanceof DataSchemaModel)) {
            return parent::getKey($model, $key);
        }

    	return $model->getTable().".".$key;
    }
}
