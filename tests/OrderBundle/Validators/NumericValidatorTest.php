<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Validators;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

final class NumericValidatorTest extends TestCase
{
    /** 
     * @test 
     * @dataProvider valueProvider
     */
    public function isValid($value, $expectedResult): void
    {
        $validator = new NumericValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expectedResult, $returned);
    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsANumber' => ['value' => 10, 'expectedResult' => true],
            'shouldBeValidWhenValueIsANumberAsString' => ['value' => '20', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsNotANumber' => ['value' => 'xpto', 'expectedResult' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
        ];
    }
}
