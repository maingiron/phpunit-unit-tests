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
    private Gateway $gatewayMock;
    private PaymentTransactionRepository $repositoryMock;
    private Customer $customerMock;
    private Item $itemMock;
    private CreditCard $creditCardMock;

    private $service;
    
    public function setUp(): void
    {
        $this->gatewayMock = $this->createMock(Gateway::class);
        $this->repositoryMock = $this->createMock(PaymentTransactionRepository::class);
        $this->customerMock = $this->createMock(Customer::class);
        $this->itemMock = $this->createMock(Item::class);
        $this->creditCardMock = $this->createMock(CreditCard::class);

        $this->service = new PaymentService($this->gatewayMock, $this->repositoryMock);
    }

    /**
     * @test
     */
    public function shouldBeSaveWhenGatewayReturnedOkWithRetries(): void
    {
        $this->gatewayMock->expects(self::atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, true
            ));

        $this->repositoryMock->expects($this->once())
            ->method('save');

        $this->service = new PaymentService($this->gatewayMock, $this->repositoryMock);

        $this->service->pay($this->customerMock, $this->itemMock, $this->creditCardMock);
    }

    /**
     * @test
     */
    public function shouldBeThrowExceptionWhenGatewayFailed(): void
    {
        $this->gatewayMock->expects(self::atLeast(3))
            ->method('pay')
            ->will($this->onConsecutiveCalls(
                false, false, false
            ));

        $this->repositoryMock = $this->createMock(PaymentTransactionRepository::class);
        $this->repositoryMock->expects($this->never())
            ->method('save');

        self::expectException(PaymentErrorException::class);

        $this->service = new PaymentService($this->gatewayMock, $this->repositoryMock);

        $this->service->pay($this->customerMock, $this->itemMock, $this->creditCardMock);
    }
}
