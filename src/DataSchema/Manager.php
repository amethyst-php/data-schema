<?php

namespace Amethyst\DataSchema;

use Railken\Lem\Manager as BaseManager;
use Amethyst\Models\DataSchema;
use Amethyst\DataSchema\Helper;
use Railken\Lem\Faker;
use Railken\Lem\Validator;
use Railken\Lem\Authorizer;
use Railken\Lem\Serializer;
use Railken\Lem\Repository;
use Amethyst\DataSchema\Model;
use Amethyst\DataSchema\Schema;
use Railken\Lem\Contracts\AgentContract;

class Manager extends BaseManager
{
    protected $dataSchema;

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->setAgent($agent);
    }

    /**
     * Initialize the model by the configuration.
     */
    public function bootConfig()
    {
        $this->comment = "";
    }

    /**
     * Register Classes.
     */
    public function registerClasses()
    {
    	$name = $this->dataSchema->name;

        return [
		    'table'      => Helper::toTable($name),
		    'comment'    => '',
		    'model'      => Model::class,
		    'schema'     => Schema::class,
		    'repository' => Repository::class,
		    'serializer' => Serializer::class,
		    'validator'  => Validator::class,
		    'authorizer' => Authorizer::class,
		    'faker'      => Faker::class
		];
    }

    public function reloadByDataSchema(DataSchema $dataSchema)
    {
    	$this->dataSchema = $dataSchema;
    	$this->boot();
    }

    public function newEntity(array $parameters = [])
    {
    	$model = new Model($parameters);
    	$model->setTable(Helper::toTable($this->dataSchema->name));

    	return $model;
    }
}
