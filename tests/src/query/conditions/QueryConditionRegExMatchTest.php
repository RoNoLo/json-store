<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'isRegExMatch' aka '$regex' conditions.
 *
 * Usage:
 *   field => [ '$in' => array|string ]
 * Translated to:
 *   is field-string in query-string? or
 *   is field-value in query-array
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionRegExMatchTest extends QueryTestBase
{
    /**
     * PHP equivalent is: strpos($comparable, $value) !== false
     *
     * SELECT * FROM store WHERE ...;
     */
    public function testCommandRegExMatchWithString1()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "email" => [
                    '$regex' => "/\.com/",
                ]
            ])
            ->execute()
        ;

        $expected = 83;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE ...;
     */
    public function testCommandRegExMatchWithString2()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "email" => [
                    '$regex' => "/\.biz/",
                ]
            ])
            ->execute()
        ;

        $expected = 151;

        $this->assertEquals($expected, $result->count());
    }
}
