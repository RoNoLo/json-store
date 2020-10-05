<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'isEqual' condition in simple and
 * command form. This is also a reference how to use it and
 * which value types are are supported.
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionGreaterThanOrEqualOrEqualTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE index >= 500;
     */
    public function testCommandGreaterThanOrEqualWithInt()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "index" => [
                    '$gte' => 500,
                ]
            ])
            ->execute()
        ;

        $expected = 500;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor >= "green";
     */
    public function testCommandGreaterThanOrEqualWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$gte' => "green",
                ]
            ])
            ->execute()
        ;

        $expected = 338;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance >= 3725.49;
     */
    public function testCommandGreaterThanOrEqualWithFloat()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => [
                    '$gte' => 3725.49,
                ]
            ])
            ->execute()
        ;

        $expected = 84;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name > { first: "Morales", last: "Levy" };
     *
     * Note: it is up to PHP to decide what $value > $comparable for example
     *       { first: "Emma", last: "John" } > { first: "Morales", last: "Levy" }
     *       is as a boolean result.
     */
    public function testSimpleEqualsWithObjectAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => [
                    '$gte' => (object) [
                        "first" => "Morales",
                        "last" => "Levy"
                    ],
                ],
            ])
            ->execute()
        ;

        $expected = 326;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE registered > "2009-07-01T09:50:13";
     */
    public function testCommandWithDates()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "registered" => [
                    '$gte' => new \DateTime("2009-08-01T09:50:13"),
                ]
            ])
            ->execute()
        ;

        $expected = 387;

        $this->assertEquals($expected, $result->count());
    }
}
