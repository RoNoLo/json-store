<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'IsNotIn' aka '$nin' conditions.
 *
 * Usage:
 *   field => [ '$nin' => array|string ]
 * Translated to:
 *   is field-string not in query-string? or
 *   is field-value not in query-array
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionIsNotInTest extends QueryTestBase
{
    /**
     * PHP equivalent is: strpos($comparable, $value) === false
     *
     * SELECT * FROM store WHERE ...;
     */
    public function testCommandIsInWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$nin' => "lightbrown",
                ]
            ])
            ->execute()
        ;

        $expected = 663;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor NOT IN ( "brown", "green" );
     */
    public function testCommandIsInWithArrayOfString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$nin' => [
                        "brown",
                        "green"
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 325;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE age NOT IN ( 22, 30 );
     */
    public function testCommandIsInWithArrayOfInt()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "age" => [
                    '$nin' => [
                        22,
                        30
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 956;

        $this->assertEquals($expected, $result->count());
    }
}
