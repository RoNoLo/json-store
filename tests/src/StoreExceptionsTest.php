<?php

namespace RoNoLo\JsonStorage;

use Exception;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use RoNoLo\JsonStorage\Exception\DocumentNotFoundException;
use RoNoLo\JsonStorage\Exception\DocumentNotStoredException;
use RoNoLo\JsonStorage\Store\Config;

class StoreExceptionsTest extends TestBase
{
    public function testPutWillThrowExceptionWhenStoringArray()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $this->expectException(DocumentNotStoredException::class);
        $this->expectExceptionMessage("Your data was not an single object. (Maybe an array, you may use ->putMany() instead.)");

        $data[] = [
            'slug' => '123',
            'body' => 'THIS IS BODY TEXT',
            'random' => rand(100, 20000)
        ];

        $store->put($data);
    }

    public function testPutManyWillThrowExceptionWhenStoringObject()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $this->expectException(DocumentNotStoredException::class);
        $this->expectExceptionMessage("Your data was not an array of objects. (To store objects use ->put() instead.)");

        $data = [
            'slug' => '123',
            'body' => 'THIS IS BODY TEXT',
            'random' => rand(100, 20000)
        ];

        $store->putMany($data);
    }

    public function testReadWillThrowExceptionWhenReadNonExistingDocument()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $this->expectException(DocumentNotFoundException::class);
        $this->expectExceptionMessage("Document with id `123456` not found.");

        $store->read("123456");
    }
}
