<?php

namespace RoNoLo\JsonStorage\Store;

use League\Flysystem\FilesystemAdapter;

interface StoreConfig
{
    public function setAdapter(FilesystemAdapter $adapter);

    public function getAdapter(): FilesystemAdapter;
}