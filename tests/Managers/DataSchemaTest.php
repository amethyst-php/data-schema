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

    public function testBasicDataCycle()
    {
        $data = (new DataSchemaManager())->createOrFail([
            'name' => 'my-new-data',
        ])->getResource();

        $manager = app('amethyst')->findManagerByName($data->name);

        // No fields needed: id,created_at,updated_at
        $resource = $manager->createOrFail([])->getResource();
        $this->assertEquals(true, !empty($resource->id));

        $data->name = 'renaming-is-difficult';
        $data->save();

        $resource = $manager->createOrFail([])->getResource();
        $this->assertEquals(true, !empty($resource->id));

        $manager = app('amethyst')->findManagerByName($data->name);

        $this->assertEquals(2, $manager->getRepository()->findAll()->count());

        $data->delete();

        // Table doesn't exists anymore
        $this->expectException(\Illuminate\Database\QueryException::class);
        $this->assertEquals(2, $manager->getRepository()->findAll()->count());
    }

    public function testDataRenaming()
    {
        $data = (new DataSchemaManager())->createOrFail([
            'name' => 'my-new-data',
        ])->getResource();

        $data->name = 'renaming-is-difficult';
        $data->save();

        $this->expectException(\Amethyst\Core\Exceptions\DataNotFoundException::class);

        $manager = app('amethyst')->findManagerByName('my-new-data');
    }

    public function testUniqueAndInvalid()
    {
        $data = (new DataSchemaManager())->create([
            'name'   => 'my-new-data',
        ]);

        $errors = (new DataSchemaManager())->create([
            'name'   => 'my-new-data',
        ])->getSimpleErrors();

        $this->assertEquals(2, count($errors));
        $this->assertEquals('DATA-SCHEMA_NAME_NOT_UNIQUE', $errors[0]['code']);
        $this->assertEquals('DATA-SCHEMA_NAME_NOT_VALID', $errors[1]['code']);


        $errors = (new DataSchemaManager())->create([
            'name'   => 'amethyst_data_schemas',
        ])->getSimpleErrors();

        $this->assertEquals(1, count($errors));
        $this->assertEquals('DATA-SCHEMA_NAME_NOT_VALID', $errors[0]['code']);
    }


    // test rename manager
    // test delete manager
    // test with permissions?
    // test with attributes?
    // test with data-view
}
