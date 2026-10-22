<?php

namespace RoNoLo\JsonStorage;

use PHPUnit\Framework\Attributes\DataProvider;
use RoNoLo\JsonStorage\Query\ConditionProvider;

class ConditionProviderInTest extends TestBase
{
    #[DataProvider('equalProvider')]
    public function testIsIn($expected, $value, $comparable)
    {
        $conditionExecutor = new ConditionProvider();

        $condition = $conditionExecutor->isIn($value, $comparable);

        $result = $condition();

        $this->assertEquals($expected, $result);
    }

    public static function equalProvider(): array
    {
        return [
            [
                false,
                10,
                [11, 12, 13, 14]
            ],
            [
                false,
                "bernd",
                []
            ],
            [
                false,
                "heinz",
                ["luisa", "jane", "melanie", "antje"]
            ],
            [
                false,
                "heinz",
                ["Heinz"]
            ],
            [
                false,
                null,
                []
            ],
            [
                true,
                "Heinz",
                ["Heinz"]
            ],
            [
                true,
                '1',
                ['1', '123', '45']
            ],
            [
                true,
                11,
                [10, 11, 12, 14]
            ],
        ];
    }
}
