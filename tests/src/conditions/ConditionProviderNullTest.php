<?php

namespace RoNoLo\JsonStorage;

use PHPUnit\Framework\Attributes\DataProvider;
use RoNoLo\JsonStorage\Query\ConditionProvider;

class ConditionProviderNullTest extends TestBase
{
    #[DataProvider('equalProvider')]
    public function testIsNull($expected, $value)
    {
        $conditionExecutor = new ConditionProvider();

        $condition = $conditionExecutor->isNull($value, true);

        $result = $condition();

        $this->assertEquals($expected, $result);
    }

    public static function equalProvider(): array
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
