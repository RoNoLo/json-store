<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use RoNoLo\JsonStorage\Store\Config;
use RoNoLo\JsonStorage\Store\Query;

class LocalStoreTest extends StoreTestBase
{
    /** @var Filesystem */
    private $flysystem;

    private $datastoreAdapter;

    private $repoTestPath = 'repo';

    protected function setUp(): void
    {
        parent::setUp();

        $adapter = new LocalFilesystemAdapter($this->datastorePath);

        $this->flysystem = new Filesystem($adapter);
        $this->flysystem->createDirectory($this->repoTestPath);

        $this->datastoreAdapter = new LocalFilesystemAdapter($this->datastorePath . '/' . $this->repoTestPath);
    }

    public function testAddingWritingFindingWithLocalAdapter()
    {
        $config = new Config();
        $config->setAdapter($this->datastoreAdapter);

        $store = Store::create($config);

        $this->fillStore($store, $this->fixturesPath . DIRECTORY_SEPARATOR . 'store_1000_docs.json.gz');

        // Find stuff
        $query = new Query($store);

        $result = $query
            ->find([
                "age" => 20
            ])
            ->execute();

        $this->assertEquals(16, $result->count());

        // Change stuff
        foreach ($result as $id => $data) {
            $data->age = 99;

            $store->put($data);
        }

        // Find again, but 0 results
        $result = $query->find([
            "age" => 20
        ])->execute();

        $this->assertEquals(0, $result->count());

        // Find again
        $result = $query->find([
            "age" => 99
        ])->execute();

        $store->removeMany($result->getIds());

        // Find again
        $result = $query->find([
            "age" => 99
        ])->execute();

        $this->assertEquals(0, $result->count());

        $store->truncate();
    }

    protected function tearDown(): void
    {
        $this->flysystem->deleteDirectory($this->repoTestPath);

        parent::tearDown();
    }
}



