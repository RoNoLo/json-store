<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'Contains' aka '$contains' conditions.
 *
 * Note: This operator will test the field type first and picks the most
 *       suitable comparing operation based on the query-value type.
 *
 * Usage:
 *   field => [ '$contains' => array|string|int|float ]
 * Translated to:
 *   is field-string in query-string? or
 *   is field-string in query-string? or
 *   is field-value in query-array
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionNotContainsTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE ...;
     */
    public function testCommandNotContainsWithStringInString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "about" => [
                    '$nc' => "minim",
                ]
            ])
            ->execute()
        ;

        $expected = 497;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * PHP equivalent is: strpos($comparable, $value) !== false
     *
     * SELECT * FROM store WHERE ...;
     */
    public function testCommandNotContainsWithMultipleStringsInString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "about" => [
                    '$nc' => ["minim", "eu"],
                ]
            ])
            ->execute()
        ;

        $expected = 614;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
     */
    public function testCommandNotContainsWithStringInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "tags" => [
                    '$nc' => "id"
                ]
            ])
            ->execute()
        ;

        $expected = 922;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
     */
    public function testCommandNotContainsWithIntInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "range" => [
                    '$nc' => 14
                ]
            ])
            ->execute()
        ;

        $expected = 588;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
     */
    public function testCommandNotContainsWithStringArrayInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "tags" => [
                    '$nc' => [
                        "id",
                        "eu"
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 870;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
     */
    public function testCommandNotContainsWithIntArrayInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "range" => [
                    '$nc' => [
                        12,
                        17
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 474;

        $this->assertEquals($expected, $result->count());
    }
}
