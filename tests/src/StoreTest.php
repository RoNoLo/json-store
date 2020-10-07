<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Memory\MemoryAdapter;
use RoNoLo\JsonStorage\Store\Config;

class StoreTest extends TestBase
{
    public function testPutDocuments()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        for ($i = 0; $i < 5; $i++) {
            $data = [
                'slug' => '123',
                'body' => 'THIS IS BODY TEXT',
                'random' => rand(100, 20000)
            ];

            $id = $store->put($data);

            $this->assertTrue(is_string($id));
        }
    }

    public function testPutManyDocuments()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'slug' => '123',
                'body' => 'THIS IS BODY TEXT',
                'random' => rand(100, 20000)
            ];
        }

        $ids = $store->putMany($data);

        $this->assertCount(5, $ids);
    }

    public function testReadDocument()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $data = [
            'slug' => '123',
            'body' => 'THIS IS BODY TEXT',
            'random' => rand(100, 20000)
        ];

        $id = $store->put($data);

        $result = $store->read($id, true);

        $expected = $data;
        $expected['__id'] = $id;

        $this->assertEquals($expected, $result);
    }

    /**
     * This will test if invalid IDs are checked beforehand. With $check = false
     * it wont, what could be used as speed improvement.
     *
     * An iteration would result in DocumentNotFoundExceptions when looping over the DocumentIterator.
     *
     * See the beforehand check test case in the @see testReadManyDocumentWithCheck
     *
     * @throws Exception\DocumentNotStoredException
     */
    public function testReadManyDocumentWithoutCheck()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'slug' => '123',
                'body' => 'THIS IS BODY TEXT',
                'random' => rand(100, 20000)
            ];
        }

        $ids = $store->putMany($data);

        // These are not existing and will not be prechecked
        $ids[] = "123456";
        $ids[] = "789123";

        $result = $store->readMany($ids, false, false);

        $this->assertInstanceOf(DocumentIterator::class, $result);
    }

    /**
     * This will test if invalid IDs are checked beforehand. With $check = true
     * it will check if every ID is valid and if not will remove this ID from the
     * result set.
     *
     * No DocumentNotFoundExceptions will be thrown, when looping over the DocumentIterator.
     *
     * See the skipped beforehand check test case in the @see testReadManyDocumentWithoutCheck
     */
    public function testReadManyDocumentWithCheck()
    {
        $store = Store::create((new Config())->setAdapter(new MemoryAdapter()));

        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'slug' => '123',
                'body' => 'THIS IS BODY TEXT',
                'random' => rand(100, 20000)
            ];
        }

        $ids = $store->putMany($data);

        // These are not existing and will be prechecked
        $ids[] = "123456";
        $ids[] = "789123";

        $result = $store->readMany($ids, true);

        $this->assertInstanceOf(DocumentIterator::class, $result);

        foreach ($result as $id => $document) {
            $tmp = $document['slug'];
        }
    }

    /**
     * This test will check if empty directories are also deleted, if
     * a document is removed.
     */
    public function testRemoveDirectoryCleanUp()
    {
        $store = Store::create((new Config())->setAdapter(new Local($this->datastorePath . '/something')));

        $data = [];
        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'id' => $i,
                'slug' => '123',
                'body' => 'THIS IS BODY TEXT',
                'random' => rand(100, 20000)
            ];
        }

        $ids = $store->putMany($data);

        $store->removeMany($ids);
    }
}
