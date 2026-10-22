<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;

class Store00WorkflowSetupTest extends StoreWorkflowTestBase
{
    public function testCreateDirectory()
    {
        $adapter = new LocalFilesystemAdapter($this->datastorePath);
        $flysystem = new Filesystem($adapter);
        $flysystem->createDirectory($this->repoTestPath);

        $this->assertTrue($flysystem->has($this->repoTestPath));
    }
}

