<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'IsNull' aka '$null' conditions.
 *
 * Usage:
 *   field => [ '$null' => true ]
 *   field => [ '$null' => false ]
 * Translated to:
 *   does field exists, but field-value is null? (if => true)
 *   does field exists, but field-value is not null? (if => false)
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionIsNullTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE pets IS NULL;
     */
    public function testCommandIsNullWithTrue()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "pets" => [
                    '$null' => true,
                ]
            ])
            ->execute()
        ;

        $expected = 216;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE pets IS NOT NULL;
     *
     * Note: The value could be anything.
     */
    public function testCommandIsNotNullWithTrue()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "pets" => [
                    '$null' => false,
                ]
            ])
            ->execute()
        ;

        $expected = 784;

        $this->assertEquals($expected, $result->count());
    }
}
