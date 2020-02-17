<?php

namespace Amethyst\Tests\Managers;

use Amethyst\Managers\AttributeManager;
use Amethyst\Managers\DataSchemaManager;
use Amethyst\Tests\BaseTest;

class AttributeTest extends BaseTest
{
    public function testBasicAttribute()
    {
        $data = app(DataSchemaManager::class)->createOrFail([
            'name' => 'cat',
        ])->getResource();

        $manager = app('amethyst')->findManagerByName($data->name);

        $attribute = app(AttributeManager::class)->createOrFail([
            'name'   => 'name',
            'schema' => 'Text',
            'model'  => 'cat',
        ])->getResource();

        $resource = $manager->createOrFail([
            'name' => 'cat-1',
        ])->getResource();

        $this->assertEquals('cat-1', $resource->name);

        $attribute = app(AttributeManager::class)->createOrFail([
            'name'   => 'description',
            'schema' => 'Text',
            'model'  => 'cat',
        ])->getResource();

        $resource = $manager->createOrFail([
            'name'        => 'cat-2-name',
            'description' => 'cat-2-description',
        ])->getResource();

        $results = $manager->getRepository()->findAll();

        $this->assertEquals('cat-1', $results->get(0)->name);
        $this->assertEquals(null, $results->get(0)->description);
        $this->assertEquals('cat-2-name', $results->get(1)->name);
        $this->assertEquals('cat-2-description', $results->get(1)->description);

        $attribute->delete();

        $results = $manager->getRepository()->findAll();

        $this->assertEquals(null, $results->get(1)->description);
    }
}
