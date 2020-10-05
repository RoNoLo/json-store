<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'IsNotNull' aka '$nn' conditions.
 *
 * Note: internally this just calls the isNull method and reverses the bool argument.
 *
 * Usage:
 *   field => [ '$nn' => true ]
 *   field => [ '$nn' => false ]
 * Translated to:
 *   does field exists, but field-value is not null? (if => true)
 *   does field exists, but field-value is null? (if => false)
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionIsNotNullTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE pets IS NOT NULL;
     */
    public function testCommandIsNullWithTrue()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "pets" => [
                    '$nn' => true,
                ]
            ])
            ->execute()
        ;

        $expected = 784;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE pets IS NULL;
     *
     * Note: The value could be anything.
     */
    public function testCommandIsNotNullWithTrue()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "pets" => [
                    '$nn' => false,
                ]
            ])
            ->execute()
        ;

        $expected = 216;

        $this->assertEquals($expected, $result->count());
    }
}
