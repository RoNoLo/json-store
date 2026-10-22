<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use RoNoLo\JsonStorage\Store\Config;

abstract class QueryTestBase extends StoreTestBase
{
    protected $store;

    public function setUp(): void
    {
        parent::setUp();

        $this->store = Store::create((new Config())->setAdapter(new InMemoryFilesystemAdapter()));
        $this->fillStore($this->store, $this->fixturesPath . DIRECTORY_SEPARATOR . 'store_1000_docs.json.gz');
    }
}