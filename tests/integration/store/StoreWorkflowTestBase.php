<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Local\LocalFilesystemAdapter;

abstract class StoreWorkflowTestBase extends TestBase
{
    protected $documents_amount = 10000;

    protected $repoTestPath;

    /** @var Store */
    protected $store;

    protected function setUp(): void
    {
        parent::setUp();

        $this->documents_amount = require __DIR__ . DIRECTORY_SEPARATOR . 'setup.php';

        $this->repoTestPath = 'store_workflow';

        $datastoreAdapter = new LocalFilesystemAdapter($this->datastorePath . '/' . $this->repoTestPath);

        $storeConfig = new Store\Config();
        $storeConfig->setAdapter($datastoreAdapter);

        $this->store = Store::create($storeConfig);
    }
}