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
class QueryConditionIsNotEqualTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE index != 40;
     */
    public function testCommandEqualsWithInt()
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
    public function testCommandEqualsWithString()
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
    public function testCommandEqualsWithFloat()
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
     * SELECT * FROM store WHERE name != { first: "Morales", last: "Levy" };
     */
    public function testCommandEqualsWithObject()
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
     * SELECT * FROM store WHERE parents.mother.tags != [ "eiusmod", "non", "labore", "non", "deserunt" ];
     */
    public function testCommandEqualsWithArray()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "parents.mother.tags" => [
                    '$neq' => [
                        "eiusmod",
                        "non",
                        "labore",
                        "non",
                        "deserunt"
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
     *
     * Note: There is no 'simple' equivalent of comparing datetime objects.
     *       Only JSON like complex structures can be compared that way.
     */
    public function testCommandEqualsWithDates()
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
