<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'IsIn' aka '$in' conditions.
 *
 * Usage:
 *   field => [ '$in' => array|string ]
 * Translated to:
 *   is field-string in query-string? or
 *   is field-value in array
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionIsInTest extends QueryTestBase
{
    /**
     * PHP equivalent is: strpos($comparable, $value) !== false
     *
     * SELECT * FROM store WHERE ...;
     */
    public function testCommandIsInWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$in' => "lightbrown",
                ]
            ])
            ->execute()
        ;

        $expected = 337;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
     */
    public function testCommandIsInWithArrayOfString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$in' => [
                        "brown",
                        "green"
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 675;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE age IN ( 22, 30 );
     */
    public function testCommandIsInWithArrayOfInt()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "age" => [
                    '$in' => [
                        22,
                        30
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 44;

        $this->assertEquals($expected, $result->count());
    }
}
