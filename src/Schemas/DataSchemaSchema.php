<?php

namespace Amethyst\Schemas;

use Amethyst\DataSchema\Helper;
use Illuminate\Support\Facades\Schema as DBSchema;
use Railken\Lem\Attributes;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Schema;

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
                    if ($entity->name !== $value) {
                        if (DBSchema::hasTable(Helper::toTable($value))) {
                            return false;
                        }
                    }

                    return preg_match('/^[a-z][a-z0-9\-]*$/', $value);
                }),
            Attributes\LongTextAttribute::make('description'),
            Attributes\CreatedAtAttribute::make(),
            Attributes\UpdatedAtAttribute::make(),
        ];
    }
}
