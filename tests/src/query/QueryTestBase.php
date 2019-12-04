<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;
use RoNoLo\JsonStorage\Store\Config;

abstract class QueryTestBase extends StoreTestBase
{
    protected $store;

    public function setUp(): void
    {
        $this->store = Store::create((new Config())->setAdapter(new MemoryAdapter()));
        $this->fillStore($this->store, $this->fixturesPath . DIRECTORY_SEPARATOR . 'store_1000_docs.json.gz');
    }
}