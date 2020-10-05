<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'IsNotEmpty' aka '$ne' conditions.
 *
 * Note: internally this just calls the isEmpty method and reverses the bool argument.
 *
 * Usage:
 *   field => [ '$ne' => true ]
 *   field => [ '$ne' => false ]
 * Translated to:
 *   does field exists, but field-string-value is not empty? (if => true)
 *   does field exists, but field-array-value is not empty? (if => true)
 *   does field exists, but field-value is empty? (if => false)
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionIsNotEmptyTest extends QueryTestBase
{
    /**
     * SELECT * FROM store WHERE quote = '';
     */
    public function testCommandIsEmptyWithTrue()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "quote" => [
                    '$ne' => true,
                ]
            ])
            ->execute()
        ;

        $expected = 815;

        $this->assertEquals($expected, $result->count());
    }

    /**
     * SELECT * FROM store WHERE quote != '';
     */
    public function testCommandIsNotNullWithTrue()
    {
        $query = new Query($this->store);
        $result = $query
            ->find([
                "quote" => [
                    '$ne' => false,
                ]
            ])
            ->execute()
        ;

        $expected = 185;

        $this->assertEquals($expected, $result->count());
    }
}
