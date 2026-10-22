<?php

namespace RoNoLo\JsonStorage;

use PHPUnit\Framework\Attributes\DataProvider;
use RoNoLo\JsonStorage\Query\ConditionProvider;

class ConditionProviderNotNullTest extends TestBase
{
    #[DataProvider('equalProvider')]
    public function testIsNotNull($expected, $value)
    {
        $conditionExecutor = new ConditionProvider();

        $condition = $conditionExecutor->isNull($value, false);

        $result = $condition();

        $this->assertEquals($expected, $result);
    }

    public static function equalProvider(): array
    {
        return [
            [
                true,
                10,
            ],
            [
                false,
                null,
            ],
            [
                true,
                "heinz",
            ],
            [
                true,
                "heinz",
            ],
            [
                true,
                "Heinz",
            ],
            [
                true,
                [],
            ],
            [
                true,
                11,
            ],
            [
                true,
                11.5,
            ],
            [
                true,
                "\0",
            ],
            [
                true,
                new \stdClass(),
            ],
            [
                true,
                "2020-01-01 00:00:00",
            ],
            [
                true,
                new \DateTime("2020-01-02"),
            ],
        ];
    }
}
