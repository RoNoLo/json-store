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
class QueryConditionsIsNotEqualTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE index != 40;
     */
    public function testSimpleEqualsWithIntAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "index" => [
                    '$neq' => 40
                ],
            ])
            ->execute()
        ;

        $expected = 999;

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
                    '$neq' => "green",
                ],
            ])
            ->execute()
        ;

        $expected = 662;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE balance != 1086.98;
     */
    public function testSimpleEqualsWithFloatAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "balance" => [
                    '$neq' => 1086.98
                ]
            ])
            ->execute()
        ;

        $expected = 999;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE name != { first: "Morales", last: "levy" };
     */
    public function testSimpleEqualsWithObjectAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "name" => [
                    '$neq' => (object) [
                        "first" => "Morales",
                        "last" => "Levy"
                    ],
                ],
            ])
            ->execute()
        ;

        $expected = 999;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE registered != "2009-07-01T09:50:13";
     */
    public function testSimpleEqualsWithDatesAsSpecialCommand()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "registered" => [
                    '$neq' => new \DateTime("2009-07-01T09:50:13"),
                ]
            ])
            ->execute()
        ;

        $expected = 999;

        $this->assertEquals($expected, $result->count());
    }
}
