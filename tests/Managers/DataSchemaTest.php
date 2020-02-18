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

        $data = (new DataSchemaManager())->updateOrFail($data, [
            'name' => 'renaming-is-difficult',
        ])->getResource();

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

        $data = (new DataSchemaManager())->updateOrFail($data, [
            'name' => 'my-new-data',
        ])->getResource();

        $data = (new DataSchemaManager())->updateOrFail($data, [
            'name' => 'renaming-is-difficult',
        ])->getResource();

        $this->expectException(\Amethyst\Core\Exceptions\DataNotFoundException::class);

        $manager = app('amethyst')->findManagerByName('my-new-data');
    }

    public function testUniqueAndInvalid()
    {
        $data = (new DataSchemaManager())->create([
            'name' => 'my-new-data',
        ]);

        $errors = (new DataSchemaManager())->create([
            'name' => 'my-new-data',
        ])->getSimpleErrors();

        $this->assertEquals(2, count($errors));
        $this->assertEquals('DATA-SCHEMA_NAME_NOT_UNIQUE', $errors[0]['code']);
        $this->assertEquals('DATA-SCHEMA_NAME_NOT_VALID', $errors[1]['code']);

        $errors = (new DataSchemaManager())->create([
            'name' => 'amethyst_data_schemas',
        ])->getSimpleErrors();

        $this->assertEquals(1, count($errors));
        $this->assertEquals('DATA-SCHEMA_NAME_NOT_VALID', $errors[0]['code']);
    }

    public function testMorphClass()
    {
        $data = (new DataSchemaManager())->createOrFail([
            'name' => 'cat',
        ])->getResource();

        $managerCat = app('amethyst')->findManagerByName($data->name);

        $data = (new DataSchemaManager())->createOrFail([
            'name' => 'dog',
        ])->getResource();

        $managerDog = app('amethyst')->findManagerByName($data->name);

        $resource = $managerCat->createOrFail([])->getResource();
        $this->assertEquals(true, !empty($resource->id));

        $resource = $managerDog->createOrFail([])->getResource();
        $this->assertEquals(true, !empty($resource->id));

        $this->assertEquals(1, $managerCat->getRepository()->findAll()->count());
        $this->assertEquals(1, $managerDog->getRepository()->findAll()->count());

        $this->assertEquals('cat', $managerCat->getRepository()->newQuery()->first()->getMorphClass());
        $this->assertEquals('dog', $managerDog->getRepository()->newQuery()->first()->getMorphClass());
    }
}
