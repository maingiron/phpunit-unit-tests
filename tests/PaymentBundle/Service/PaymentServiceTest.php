<?php

declare(strict_types=1);

namespace Tests\PaymentBundle\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

final class PaymentServiceTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeSaveWhenGatewayReturnedOkWithRetries()
    {
        $gatewayMock = $this->createMock(Gateway::class);
        $gatewayMock->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, true
            ));

        $repositoryMock = $this->createMock(PaymentTransactionRepository::class);
        $repositoryMock->expects($this->once())
            ->method('save');

        $service = new PaymentService($gatewayMock, $repositoryMock);

        $customerMock = $this->createMock(Customer::class);
        $itemMock = $this->createMock(Item::class);
        $creditCardMock = $this->createMock(CreditCard::class);

        $service->pay($customerMock, $itemMock, $creditCardMock);
    }

    /**
     * @test
     */
    public function shouldBeThrowExceptionWhenGatewayFailed()
    {
        $gatewayMock = $this->createMock(Gateway::class);
        $gatewayMock->expects($this->atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, false
            ));

        $repositoryMock = $this->createMock(PaymentTransactionRepository::class);
        $repositoryMock->expects($this->never())
            ->method('save');

        $this->expectException(PaymentErrorException::class);

        $service = new PaymentService($gatewayMock, $repositoryMock);

        $customerMock = $this->createMock(Customer::class);
        $itemMock = $this->createMock(Item::class);
        $creditCardMock = $this->createMock(CreditCard::class);

        $service->pay($customerMock, $itemMock, $creditCardMock);
    }
}
