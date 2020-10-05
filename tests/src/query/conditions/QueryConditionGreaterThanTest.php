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
     * SELECT * FROM store WHERE index = 40;
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
     * SELECT * FROM store WHERE eyeColor = "green";
     */
    public function testSimpleEqualsWithString()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => "green",
            ])
            ->execute()
        ;

        $expected = 338;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE eyeColor = "green";
     */
    public function testSimpleEqualsWithStringAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "eyeColor" => [
                    '$eq' => "green",
                ]
            ])
            ->execute()
        ;

        $expected = 338;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance = 1086.98;
     */
    public function testSimpleEqualsWithFloat()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => 1086.98,
            ])
            ->execute()
        ;

        $expected = 1;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance = 1086.98;
     */
    public function testSimpleEqualsWithFloatAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => [
                    '$eq' => 1086.98
                ]
            ])
            ->execute()
        ;

        $expected = 1;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name = { first: "Morales", last: "levy" };
     */
    public function testSimpleEqualsWithObject()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => (object) [
                    "first" => "Morales",
                    "last" => "Levy"
                ],
            ])
            ->execute()
        ;

        $expected = 1;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name = { first: "Morales", last: "levy" };
     */
    public function testSimpleEqualsWithObjectAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => [
                    '$eq' => (object) [
                        "first" => "Morales",
                        "last" => "Levy"
                    ],
                ],
            ])
            ->execute()
        ;

        $expected = 1;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE registered = "2009-07-01T09:50:13";
     */
    public function testSimpleEqualsWithDatesAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "registered" => [
                    '$eq' => new \DateTime("2009-07-01T09:50:13"),
                ]
            ])
            ->execute()
        ;

        $expected = 1;

        $this->assertEquals($expected, $result->count());
    }
}
