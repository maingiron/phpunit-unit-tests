<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Validators;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

final class NotEmptyValidatorTest extends TestCase
{
    /** 
     * @test 
     * @dataProvider valueProvider
     */
    public function isValid($value, $expectedResult): void
    {
        $validator = new NotEmptyValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expectedResult, $returned);
    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsNotEmpty' => ['value' => 'xpto', 'expectedResult' => true],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false]
        ];
    }
}
