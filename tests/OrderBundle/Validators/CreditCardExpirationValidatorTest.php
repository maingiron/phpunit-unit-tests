<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Validators;

use \Datetime;
use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

final class CreditCardExpirationValidatorTest extends TestCase
{
    /** 
     * @test 
     * @dataProvider valueProvider
     */
    public function isValid($value, $expected): void
    {
        $validator = new CreditCardExpirationValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expected, $returned);
    }

    public function valueProvider(): array
    {
        $tomorrow = $this->createDateWithModify('+1 day');
        $yesterday = $this->createDateWithModify('-1 day');

        return [
            'shouldBeValidWhenDateIsNotExpired' => ['value' => $tomorrow, 'expected' => true],
            'shouldNotBeValidWhenDateIsExpired' => ['value' => $yesterday, 'expected' => false]
        ];
    }

    private function createDateWithModify($modify): object
    {
        $date = new DateTime();
        return $date->modify($modify);
    }
}
