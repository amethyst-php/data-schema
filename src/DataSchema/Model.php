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

    public static function bootDynamicRelations()
    {
        static::$dynamicRelations = new RelationStore();
    }
    
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
     * Create a new instance of the given model.
     *
     * @param array $attributes
     * @param bool  $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);

        $model->setTable($this->getTable());
        $model->setManager($this->retrieveManager());

        return $model;
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
