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
class QueryConditionContainsTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE ...;
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
     * PHP equivalent is: strpos($comparable, $value) !== false
     *
     * SELECT * FROM store WHERE ...;
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
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
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
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
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
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
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
     * SELECT * FROM store WHERE eyeColor IN ( "brown", "green" );
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
