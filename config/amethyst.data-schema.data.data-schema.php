<?php

return [
    'table'      => 'data_schema',
    'comment'    => 'DataSchema',
    'model'      => Amethyst\Models\DataSchema::class,
    'schema'     => Amethyst\Schemas\DataSchemaSchema::class,
    'repository' => Amethyst\Repositories\DataSchemaRepository::class,
    'serializer' => Amethyst\Serializers\DataSchemaSerializer::class,
    'validator'  => Amethyst\Validators\DataSchemaValidator::class,
    'authorizer' => Amethyst\Authorizers\DataSchemaAuthorizer::class,
    'faker'      => Amethyst\Fakers\DataSchemaFaker::class,
    'manager'    => Amethyst\Managers\DataSchemaManager::class,
];
