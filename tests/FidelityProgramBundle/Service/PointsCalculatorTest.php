<?php

declare(strict_types=1);

namespace Tests\FidelityProgramBundle\Service;

use FidelityProgramBundle\Service\PointsCalculator;
use PHPUnit\Framework\TestCase;

final class PointsCalculatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider pointsProvider
     */
    public function pointsToReceive($value, $expected)
    {
        $service = new PointsCalculator();

        $returned = $service->calculatePointsToReceive($value);

        self::assertEquals($expected, $returned);
    }

    public function pointsProvider()
    {
        return [
            [30, 0],
            [51, 1020],
            [71, 2130],
            [101, 5050]
        ];
    }
}
