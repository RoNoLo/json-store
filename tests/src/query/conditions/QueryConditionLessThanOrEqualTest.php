<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'LessThanOrEqual' aka '$lte' conditions.
 *
 * Usage:
 *   field => [ '$lte' => int|float|datetime|string ]
 * Translated to:
 *   is field-int less than or equal query-int? or
 *   is field-float less than  or equalquery-float? or
 *   is field-datetime less than or equal query-datetime? or
 *   is field-string less than or equal query-string?
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionLessThanOrEqualTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE index <= 500;
     */
    public function testCommandLessThanOrEqualWithInt()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "index" => [
                    '$lte' => 500,
                ]
            ])
            ->execute()
        ;

        $expected = 501;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor <= "green";
     */
    public function testCommandLessThanOrEqualWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$lte' => "green",
                ]
            ])
            ->execute()
        ;

        $expected = 1000;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance <= 3725.49;
     */
    public function testCommandLessThanOrEqualWithFloat()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => [
                    '$lte' => 3725.49,
                ]
            ])
            ->execute()
        ;

        $expected = 917;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name <= { first: "Morales", last: "Levy" };
     *
     * Note: it is up to PHP to decide what $value > $comparable for example
     *       { first: "Emma", last: "John" } <= { first: "Morales", last: "Levy" }
     *       is as a boolean result.
     */
    public function testCommandEqualsWithObject()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => [
                    '$lte' => (object) [
                        "first" => "Morales",
                        "last" => "Levy"
                    ],
                ],
            ])
            ->execute()
        ;

        $expected = 675;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE registered <= "2009-08-01T09:50:13";
     */
    public function testCommandWithDates()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "registered" => [
                    '$lte' => new \DateTime("2009-08-01T09:50:13"),
                ]
            ])
            ->execute()
        ;

        $expected = 613;

        $this->assertEquals($expected, $result->count());
    }
}
