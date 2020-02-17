<?php

namespace Amethyst\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Amethyst\Core\ConfigurableModel;
use Railken\Lem\Contracts\EntityContract;

class DataSchema extends Model implements EntityContract
{
    use SoftDeletes, ConfigurableModel;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->ini('amethyst.data-schema.data.data-schema');
        parent::__construct($attributes);
    }
}
