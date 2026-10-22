<?php

namespace RoNoLo\JsonStorage\Store;

use League\Flysystem\FilesystemAdapter;

class Config implements StoreConfig
{
    private FilesystemAdapter $adapter;

    public function setAdapter(FilesystemAdapter $adapter): self
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter(): FilesystemAdapter
    {
        return $this->adapter;
    }
}