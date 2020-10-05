<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Store\Query;

/**
 * List of Tests to test the 'IsEmpty' aka '$empty' conditions.
 *
 * Usage:
 *   field => [ '$empty' => true ]
 *   field => [ '$empty' => false ]
 * Translated to:
 *   does field exists, but field-string-value is empty? (if => true)
 *   does field exists, but field-array-value is empty? (if => true)
 *   does field exists, but field-value is not empty? (if => false)
 *
 * @package RoNoLo\JsonStorage
 */
class QueryConditionIsEmptyTest extends QueryTestBase
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
                    '$empty' => true,
                ]
            ])
            ->execute()
        ;

        $expected = 185;

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
                    '$empty' => false,
                ]
            ])
            ->execute()
        ;

        $expected = 815;

        $this->assertEquals($expected, $result->count());
    }
}
