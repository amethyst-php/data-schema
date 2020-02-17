<?php

namespace Amethyst\DataSchema;

use Amethyst\Core\ConfigurableModel;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;

class Model extends BaseModel implements EntityContract
{
    use ConfigurableModel;

    protected $__manager;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function retrieveManager()
    {
        return $this->__manager;
    }

    public function setManager(ManagerContract $manager)
    {
        $this->__manager = $manager;
    }

    public function retrieveTableName()
    {
        return $this->table;
    }
}
