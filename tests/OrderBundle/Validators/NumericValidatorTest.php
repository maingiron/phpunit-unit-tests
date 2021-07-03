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
    public function isValid($value, $expected): void
    {
        $validator = new NumericValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expected, $returned);
    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsANumber' => ['value' => 10, 'expected' => true],
            'shouldBeValidWhenValueIsANumberAsString' => ['value' => '20', 'expected' => true],
            'shouldNotBeValidWhenValueIsNotANumber' => ['value' => 'xpto', 'expected' => false],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expected' => false],
        ];
    }
}
