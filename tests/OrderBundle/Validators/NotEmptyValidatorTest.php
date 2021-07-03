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
    public function isValid($value, $expected): void
    {
        $validator = new NotEmptyValidator($value);

        $returned = $validator->isValid();

        self::assertEquals($expected, $returned);
    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsNotEmpty' => ['value' => 'xpto', 'expected' => true],
            'shouldNotBeValidWhenValueIsEmpty' => ['value' => '', 'expected' => false]
        ];
    }
}
