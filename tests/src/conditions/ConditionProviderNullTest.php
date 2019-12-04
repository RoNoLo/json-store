<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Query\ConditionProvider;

class ConditionProviderNullTest extends TestBase
{
    /**
     * @dataProvider equalProvider
     *
     * @param $expected
     * @param $value
     * @param $comparable
     */
    public function testIsNull($expected, $value)
    {
        $conditionExecutor = new ConditionProvider();

        $condition = $conditionExecutor->isNull($value);

        $result = $condition();

        $this->assertEquals($expected, $result);
    }

    public function equalProvider()
    {
        return [
            [
                false,
                10,
            ],
            [
                true,
                null,
            ],
            [
                false,
                "heinz",
            ],
            [
                false,
                "heinz",
            ],
            [
                false,
                "Heinz",
            ],
            [
                false,
                [],
            ],
            [
                false,
                11,
            ],
            [
                false,
                11.5,
            ],
            [
                false,
                "\0",
            ],
            [
                false,
                new \stdClass(),
            ],
            [
                false,
                "2020-01-01 00:00:00",
            ],
            [
                false,
                new \DateTime("2020-01-02"),
            ],
        ];
    }
}
