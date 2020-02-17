<?php

namespace Amethyst\DataSchema;

use Amethyst\Core\ConfigurableModel;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;
use Illuminate\Database\Eloquent\Relations\Relation;

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

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass()
    {
        if ($manager = $this->retrieveManager()) {
            return $manager->getName();
        }

        return parent::getMorphClass();
    }
}
