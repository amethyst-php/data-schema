<?php

namespace Amethyst\DataSchema;

use Amethyst\Core\ConfigurableModel;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Railken\Lem\Contracts\EntityContract;

class Model extends BaseModel implements EntityContract
{
    use ConfigurableModel;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // $this->ini('amethyst.data-schema.data.data-schema');
        parent::__construct($attributes);
    }
}
