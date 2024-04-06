<?php

namespace Amethyst\DataSchema;

use Amethyst\Models\DataSchema;
use Railken\Lem\Agents;
use Railken\Lem\Authorizer;
use Railken\Lem\Contracts\AgentContract;
use Railken\Lem\Faker;
use Railken\Lem\Manager as BaseManager;
use Railken\Lem\Repository;
use Railken\Lem\Serializer;
use Railken\Lem\Validator;

class Manager extends BaseManager
{
    protected $dataSchema;

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null, bool $boot = true)
    {
        if (!$agent) {
            $agent = new Agents\SystemAgent();
        }

        $this->setAgent($agent);

        if ($boot) {
            $this->boot();
        }
    }

    /**
     * Initialize the model by the configuration.
     */
    public function bootConfig()
    {
        $this->comment = '';
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
            'faker'      => Faker::class,
        ];
    }

    public function reloadByDataSchema(DataSchema $dataSchema)
    {
        $this->dataSchema = $dataSchema;
        $this->boot();
    }

    public function newEntity(array $parameters = [])
    {
        $aliasName = ucfirst(str_replace("-", "_", $this->dataSchema->name));
        $namespace = "App\Models\DataSchema";
        $fullAliasName = "\\".$namespace."\\".$aliasName;

        // Possibility to write your own model
        if (!class_exists($fullAliasName)) {
            $this->newAlias($namespace, Model::class, $aliasName);
        }

        $model = new $fullAliasName($parameters);
        $model->setTable(Helper::toTable($this->dataSchema->name));
        $model->setManager($this);
        $model->ini(null, true);

        return $model;
    }

    public function newAlias($namespace, $original, $alias)
    {
        eval("namespace $namespace;\n class $alias extends \\$original {}");
    }
}
