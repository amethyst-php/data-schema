<?php

namespace Amethyst\Tests\Managers;

use Amethyst\Managers\DataSchemaManager;
use Amethyst\Managers\RelationManager;
use Amethyst\Managers\RelationSchemaManager;
use Amethyst\Tests\BaseTest;
use Symfony\Component\Yaml\Yaml;

class RelationTest extends BaseTest
{
    public function testBasicMorphToMany()
    {
        $data = app(DataSchemaManager::class)->createOrFail([
            'name' => 'dog',
        ])->getResource();

        $data = app(DataSchemaManager::class)->createOrFail([
            'name' => 'cat',
        ])->getResource();

        $relation = app(RelationSchemaManager::class)->createOrFail([
            'name'    => 'friends',
            'type'    => 'MorphToMany',
            'data'    => 'dog',
            'payload' => Yaml::dump([
                'target' => 'cat',
            ]),
        ]);

        $dog = app('amethyst')->findManagerByName('dog')->newEntity();
        $dog->save();
        $cat = app('amethyst')->findManagerByName('cat')->newEntity();
        $cat->save();

        // $dog->friends()->attach($cat);
        app(RelationManager::class)->createOrFail([
            'key'         => 'dog:friends',
            'source_type' => 'dog',
            'source_id'   => $dog->id,
            'target_type' => 'cat',
            'target_id'   => $cat->id,
        ]);

        $this->assertEquals(1, $dog->id);
        $this->assertEquals(1, $dog->friends->count());
        $this->assertEquals("select * from `ac_cat` inner join `amethyst_relations` on `ac_cat`.`id` = `amethyst_relations`.`target_id` where `amethyst_relations`.`source_id` = '1' and `amethyst_relations`.`source_type` = 'dog' and `amethyst_relations`.`target_type` = 'cat' and `amethyst_relations`.`key` = 'dog:friends'", $this->getQuery($dog->friends()));
        $this->assertEquals(1, $dog->friends()->first()->id);
    }

    public function testAvoidCollision()
    {
        $data = app(DataSchemaManager::class)->createOrFail([
            'name' => 'dog',
        ])->getResource();

        $data = app(DataSchemaManager::class)->createOrFail([
            'name' => 'cat',
        ])->getResource();

        $dog = app('amethyst')->findManagerByName('dog')->newEntity();
        $cat = app('amethyst')->findManagerByName('cat')->newEntity();

        $relation = app(RelationSchemaManager::class)->createOrFail([
            'name'    => 'friends',
            'type'    => 'MorphToMany',
            'data'    => 'dog',
            'payload' => Yaml::dump([
                'target' => 'cat',
            ]),
        ]);

        $dog->friends(); // correct

        $this->expectException(\BadMethodCallException::class);
        $cat->friends(); // incorrect
    }

    public function getQuery($builder)
    {
        return vsprintf(str_replace(['?'], ['\'%s\''], $builder->toSql()), $builder->getBindings());
    }
}
