<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'LessThan' aka '$lt' conditions.
 *
 * Usage:
 *   field => [ '$lt' => int|float|datetime|string ]
 * Translated to:
 *   is field-int less than query-int? or
 *   is field-float less than query-float? or
 *   is field-datetime less than query-datetime? or
 *   is field-string less than query-string?
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionLessThanTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE index < 500;
     */
    public function testCommandLessThanWithInt()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "index" => [
                    '$lt' => 500,
                ]
            ])
            ->execute()
        ;

        $expected = 500;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor < "green";
     */
    public function testCommandLessThanWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$lt' => "green",
                ]
            ])
            ->execute()
        ;

        $expected = 662;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance < 3725.49;
     */
    public function testCommandLessThanWithFloat()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => [
                    '$lt' => 3725.49,
                ]
            ])
            ->execute()
        ;

        $expected = 916;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name < { first: "Morales", last: "Levy" };
     *
     * Note: it is up to PHP to decide what $value > $comparable for example
     *       { first: "Emma", last: "John" } < { first: "Morales", last: "Levy" }
     *       is as a boolean result.
     */
    public function testCommandEqualsWithObject()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => [
                    '$lt' => (object) [
                        "first" => "Morales",
                        "last" => "Levy"
                    ],
                ],
            ])
            ->execute()
        ;

        $expected = 674;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE registered < "2009-08-01T09:50:13";
     */
    public function testCommandWithDates()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "registered" => [
                    '$lt' => new \DateTime("2009-08-01T09:50:13"),
                ]
            ])
            ->execute()
        ;

        $expected = 613;

        $this->assertEquals($expected, $result->count());
    }
}
