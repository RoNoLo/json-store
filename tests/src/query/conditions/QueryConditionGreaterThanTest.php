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
class QueryConditionGreaterThanTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE index > 500;
     */
    public function testCommandGreaterThanWithInt()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "index" => [
                    '$gt' => 500,
                ]
            ])
            ->execute()
        ;

        $expected = 499;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor > "green";
     */
    public function testCommandGreaterThanWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$gt' => "green",
                ]
            ])
            ->execute()
        ;

        $expected = 0;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance > 3725.49;
     */
    public function testCommandGreaterThanWithFloat()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => [
                    '$gt' => 3725.49,
                ]
            ])
            ->execute()
        ;

        $expected = 83;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name > { first: "Morales", last: "Levy" };
     *
     * Note: it is up to PHP to decide what $value > $comparable for example
     *       { first: "Emma", last: "John" } > { first: "Morales", last: "Levy" }
     *       is as a boolean result.
     */
    public function testCommandEqualsWithObject()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => [
                    '$gt' => (object) [
                        "first" => "Morales",
                        "last" => "Levy"
                    ],
                ],
            ])
            ->execute()
        ;

        $expected = 325;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE registered > "2009-08-01T09:50:13";
     */
    public function testCommandWithDates()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "registered" => [
                    '$gt' => new \DateTime("2009-08-01T09:50:13"),
                ]
            ])
            ->execute()
        ;

        $expected = 387;

        $this->assertEquals($expected, $result->count());
    }
}
