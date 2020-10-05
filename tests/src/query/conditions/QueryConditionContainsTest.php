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
 *   is query-string in field-string? or
 *   is any query-array-strings in field-string? or
 *   is any query-array-int in field-string? or
 *   is query-string in field-string-array? or
 *   is query-int in field-int-array? or
 *   is query-string-array in field-string-array? or
 *   is query-int-array in field-int-array?
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionContainsTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE ...; ???
     */
    public function testCommandContainsWithStringInString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "about" => [
                    '$contains' => "minim",
                ]
            ])
            ->execute()
        ;

        $expected = 503;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE ...; ???
     */
    public function testCommandContainsWithMultipleStringsInString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "about" => [
                    '$contains' => ["minim", "eu"],
                ]
            ])
            ->execute()
        ;

        $expected = 866;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE ...; ??
     */
    public function testCommandContainsWithStringInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "tags" => [
                    '$contains' => "id"
                ]
            ])
            ->execute()
        ;

        $expected = 78;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE ...; ??
     */
    public function testCommandContainsWithIntInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "range" => [
                    '$contains' => 14
                ]
            ])
            ->execute()
        ;

        $expected = 412;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE ...; ??
     */
    public function testCommandContainsWithStringArrayInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "tags" => [
                    '$contains' => [
                        "id",
                        "eu"
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 130;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE ...; ??
     */
    public function testCommandContainsWithIntArrayInArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "range" => [
                    '$contains' => [
                        12,
                        17
                    ]
                ]
            ])
            ->execute()
        ;

        $expected = 526;

        $this->assertEquals($expected, $result->count());
    }
}
