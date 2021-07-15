<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Service;

use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use OrderBundle\Exception\BadWordsFoundException;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Repository\OrderRepository;
use OrderBundle\Service\BadWordsValidator;
use OrderBundle\Service\OrderService;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

final class OrderServiceTest extends TestCase
{
    private BadWordsValidator $badWordsValidator;
    private PaymentService $paymentService;
    private OrderRepository $orderRepository;
    private FidelityProgramService $fidelityProgramService;
    private Customer $customer;
    private Item $item;
    private CreditCard $creditCard;

    private $orderService;

    public function setUp(): void
    {
        $this->badWordsValidator = $this->createMock(BadWordsValidator::class);
        $this->paymentService = $this->createMock(PaymentService::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->fidelityProgramService = $this->createMock(FidelityProgramService::class);
        $this->customer = $this->createMock(Customer::class);
        $this->item = $this->createMock(Item::class);
        $this->creditCard = $this->createMock(CreditCard::class);
    }

    /**
     * @test
     */
    public function shouldBeNotProcessWhenCustomerIsNotAllowed(): void
    {
        $this->withOrderService()
            ->withCustomer(false);

        self::expectException(CustomerNotAllowedException::class);

        $this->orderService->process(
            $this->customer,
            $this->item,
            'description here',
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldBeNotProcessWhenItemIsNotAvailable(): void
    {
        $this->withOrderService()
            ->withCustomer(true)
            ->withAvailableItem(false);

        self::expectException(ItemNotAvailableException::class);

        $this->orderService->process(
            $this->customer,
            $this->item,
            'description here',
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldBeNotProcessWhenBadWordsIsFound(): void
    {
        $this->withOrderService()
            ->withCustomer(true)
            ->withAvailableItem(true)
            ->withBadWordsValidator(true);

        self::expectException(BadWordsFoundException::class);

        $this->orderService->process(
            $this->customer,
            $this->item,
            'description here',
            $this->creditCard
        );
    }

    /**
     * @test
     */
    public function shouldBeSuccessfullyProcess(): void
    {
        $this->withOrderService()
            ->withCustomer(true)
            ->withAvailableItem(true)
            ->withBadWordsValidator(false);

        $transaction = $this->createMock(PaymentTransaction::class);

        $this->paymentService->method('pay')
            ->willReturn($transaction);

        $this->orderRepository->expects($this->once())
            ->method('save');

        $order = $this->orderService->process(
            $this->customer,
            $this->item,
            'description here',
            $this->creditCard
        );

        self::assertNotEmpty($order);
    }

    private function withOrderService(): object
    {
        $this->orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );

        return $this;
    }

    private function withCustomer(bool $value): object
    {
        $this->customer->method('isAllowedToOrder')
            ->willReturn($value);

        return $this;
    }

    private function withAvailableItem(bool $value): object
    {
        $this->item->method('isAvailable')
            ->willReturn($value);

        return $this;
    }

    private function withBadWordsValidator(bool $value): object
    {
        $this->badWordsValidator->method('hasBadWords')
            ->willReturn($value);

        return $this;
    }
}
