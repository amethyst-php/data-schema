<?php

namespace Amethyst\DataSchema;

use Amethyst\Core\ConfigurableModel;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Railken\Lem\Contracts\EntityContract;
use Railken\Lem\Contracts\ManagerContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

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
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        // This method just provides a convenient way for us to generate fresh model
        // instances of this current model. It is particularly useful during the
        // hydration of new objects via the Eloquent query builder instances.
        $model = new static((array) $attributes);

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

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
