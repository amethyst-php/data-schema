<?php

namespace Amethyst\Authorizers;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class DataSchemaAuthorizer extends Authorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'data-schema.create',
        Tokens::PERMISSION_UPDATE => 'data-schema.update',
        Tokens::PERMISSION_SHOW   => 'data-schema.show',
        Tokens::PERMISSION_REMOVE => 'data-schema.remove',
    ];
}
