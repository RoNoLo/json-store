<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\{DocumentIterator, Query, Result};

class ResultTest extends QueryTestBase
{
    public function testCanCreateEmptyResult()
    {
        $result = Result::fromJson($this->store);

        $this->assertInstanceOf(Result::class, $result);
    }

    public function testCanCreateResult()
    {
        $result = Result::fromJson($this->store, [
            "ids" => ["1", "2", "3"],
            "total" => 30,
        ]);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals(3, $result->count());
        $this->assertEquals(30, $result->total());
    }

    public function testCanGetIdsFromResult()
    {
        $result = Result::fromJson($this->store, [
            "ids" => ["a1", "a2", "a3"],
            "total" => 30,
        ]);

        $ids = $result->getIds();

        $this->assertEquals("a1", array_shift($ids));
        $this->assertEquals("a2", array_shift($ids));
        $this->assertEquals("a3", array_shift($ids));
    }

    public function testCanGetDocumentIteratorFromResult()
    {
        $result = Result::fromJson($this->store, [
            "ids" => [
                "4950418449d0d9995d67f5",
                "67168659162cd9995d67f5",
                "048745826d3ce9995d67f5"
            ],
            "total" => 30,
        ]);

        $documentIterator = $result->getIterator();

        $this->assertInstanceOf(DocumentIterator::class, $documentIterator);
    }

    public function testCanCountResultAsArray()
    {
        $result = Result::fromJson($this->store, [
            "ids" => ["a1", "a2", "a3"],
            "total" => 30,
        ]);

        $this->assertEquals(3, count($result));
    }

    public function testCanAccessResultAsArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                ["age" => 20],
                ["age" => 30],
                ["age" => 40]
            ])
            ->sort("guid", "asc")
            ->execute()
        ;

        $this->assertEquals("0477c5ff-ba2c-4563-8d38-70c476ea83e7", $result[1]->guid);
        $this->assertEquals("3d8e11ed-6c04-4673-8fe2-cf13eac228f8", $result[10]->guid);
    }

    public function testCanSaveResultAsJson()
    {
        $result = Result::fromJson($this->store, [
            "ids" => ["a1", "a2", "a3"],
            "total" => 30,
        ]);

        $actual = json_encode($result);

        $this->assertJson('{"ids":["a1","a2","a3"],"fields":[],"total":30,"assoc":false}', $actual);
    }
}
