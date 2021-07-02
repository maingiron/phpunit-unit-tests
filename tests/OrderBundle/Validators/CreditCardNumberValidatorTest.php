<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Validators;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

final class CreditCardNumberValidatorTest extends TestCase
{
    /** 
     * @test 
     * @dataProvider valueProvider
     */
    public function isValid($value, $expectedResult): void
    {
        $validator = new CreditCardNumberValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expectedResult, $returned);
    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenIsACreditCard' => ['value' => 4514167699712756, 'expectedResult' => true],
            'shouldBeValidWhenIsACreditCardAsString' => ['value' => '4514167699712756', 'expectedResult' => true],
            'shouldBeValidWhenIsANotCreditCard' => ['value' => 123, 'expectedResult' => false],
            'shouldBeValidWhenIsANotCreditCardAsString' => ['value' => '123', 'expectedResult' => false],
            'shouldBeValidWhenIsEmpty' => ['value' => '', 'expectedResult' => false]
        ];
    }
}
