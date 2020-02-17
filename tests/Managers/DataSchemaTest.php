<?php

namespace Amethyst\Tests\Managers;

use Amethyst\Fakers\DataSchemaFaker;
use Amethyst\Managers\DataSchemaManager;
use Amethyst\Tests\BaseTest;
use Railken\Lem\Support\Testing\TestableBaseTrait;

class DataSchemaTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = DataSchemaManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = DataSchemaFaker::class;
}
