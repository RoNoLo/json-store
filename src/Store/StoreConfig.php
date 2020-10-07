<?php

namespace RoNoLo\JsonStorage\Store;

use League\Flysystem\AdapterInterface;

interface StoreConfig
{
    public function setAdapter(AdapterInterface $adapter);

    public function getAdapter(): AdapterInterface;
}