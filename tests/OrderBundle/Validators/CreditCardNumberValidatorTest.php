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
    public function isValid($value, $expected): void
    {
        $validator = new CreditCardNumberValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expected, $returned);
    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenIsACreditCard' => ['value' => 4514167699712756, 'expected' => true],
            'shouldBeValidWhenIsACreditCardAsString' => ['value' => '4514167699712756', 'expected' => true],
            'shouldBeValidWhenIsANotCreditCard' => ['value' => 123, 'expected' => false],
            'shouldBeValidWhenIsANotCreditCardAsString' => ['value' => '123', 'expected' => false],
            'shouldBeValidWhenIsEmpty' => ['value' => '', 'expected' => false]
        ];
    }
}
