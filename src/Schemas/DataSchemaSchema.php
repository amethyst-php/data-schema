<?php

namespace Amethyst\Schemas;

use Railken\Lem\Attributes;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Schema;
use Illuminate\Support\Facades\Schema as DBSchema;
use Amethyst\DataSchema\Helper;

class DataSchemaSchema extends Schema
{
    /**
     * Get all the attributes.
     *
     * @var array
     */
    public function getAttributes()
    {
        return [
            Attributes\IdAttribute::make(),
            Attributes\TextAttribute::make('name')
                ->setRequired(true)
                ->setUnique(true)
                ->setValidator(function (EntityContract $entity, $value) {

                    if ($entity->value !== $value) {
                        if (DBSchema::hasTable(Helper::toTable($value))) {
                            return false;
                        }  
                    }

                    return preg_match('/^([a-z0-9\-]*)$/', $value);
                }),
            Attributes\LongTextAttribute::make('description'),
            Attributes\CreatedAtAttribute::make(),
            Attributes\UpdatedAtAttribute::make(),
        ];
    }
}
