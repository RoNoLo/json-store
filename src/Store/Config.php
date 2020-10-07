<?php

namespace RoNoLo\JsonStorage\Store;

use League\Flysystem\AdapterInterface;

class Config implements StoreConfig
{
    /** @var AdapterInterface */
    private $adapter;

    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }
}