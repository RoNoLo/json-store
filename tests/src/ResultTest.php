<?php

namespace RoNoLo\JsonStorage;

class ResultTest extends TestBase
{
    public function testCanCreateResult()
    {
        // TODO: add some tests
        $this->markTestSkipped("Add Result Tests later");

//        $json = file_get_contents(__DIR__ . '/../fixtures/store_1000_docs.json');
//        $gz = gzencode($json, 9);
//        file_put_contents(__DIR__ . '/../fixtures/store_1000_docs.json.gz', $gz);

        return;

        /*
        $data = json_decode($json, true);

        foreach ($data as &$item) {
            $item['balance'] = floatval($item['balance']);
            $item['latitude'] = floatval($item['latitude']);
            $item['longitude'] = floatval($item['longitude']);
        }

        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents(__DIR__ . '/../fixtures/query_1000_docs.json', $json);
        */
    }
}
