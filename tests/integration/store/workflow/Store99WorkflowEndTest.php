<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;

class Store99WorkflowEndTest extends StoreWorkflowTestBase
{
    public function testRemoveDirectory()
    {
        $adapter = new LocalFilesystemAdapter($this->datastorePath);
        $flysystem = new Filesystem($adapter);
        $flysystem->deleteDirectory($this->repoTestPath);

        $this->assertFalse($flysystem->has($this->repoTestPath));

        if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'tmp.json')) {
            unlink(__DIR__ . DIRECTORY_SEPARATOR . 'tmp.json');
        }
    }
}


