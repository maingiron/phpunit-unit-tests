<?php

declare(strict_types=1);

namespace Tests\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    /**
     * @test
     * @dataProvider customerProvider
     */
    public function isAllowedToOrder($isActive, $isBlocked, $name, $phone, $expected)
    {
        $customer = new Customer(
            $isActive, 
            $isBlocked, 
            $name, 
            $phone
        );

        $returned = $customer->isAllowedToOrder();

        self::assertEquals($expected, $returned);
    }

    public function customerProvider()
    {
        return [
            'shouldBeAllowedWhenIsActiveAndNotBlocked' => [
                'isActive' => true,
                'isBlocked' => false,
                'name' => 'Optimus Prime',
                'phone' => +5522000000000,
                'expected' => true
            ],
            'shouldNotBeAllowedWhenIsActiveButIsBlocked' => [
                'isActive' => true,
                'isBlocked' => true,
                'name' => 'Megatron',
                'phone' => +5522111111111,
                'expected' => false
            ],
            'shouldNotBeAllowedWhenIsNotActive' => [
                'isActive' => false,
                'isBlocked' => false,
                'name' => 'Bumblebee',
                'phone' => +5522222222222,
                'expected' => false
            ],
            'shouldNotBeAllowedWhenIsNotActiveAndIsBlocked' => [
                'isActive' => false,
                'isBlocked' => true,
                'name' => 'Galvatron',
                'phone' => +5533333333333,
                'expected' => false
            ]
        ];
    }
}
