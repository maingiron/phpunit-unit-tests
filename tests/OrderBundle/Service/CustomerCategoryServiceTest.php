<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Service;

use OrderBundle\Entity\Customer;
use OrderBundle\Service\CustomerCategoryService;
use OrderBundle\Service\HeavyUserCategory;
use OrderBundle\Service\LightUserCategory;
use OrderBundle\Service\MediumUserCategory;
use OrderBundle\Service\NewUserCategory;
use PHPUnit\Framework\TestCase;

final class CustomerCategoryServiceTest extends TestCase
{
    private $service;

    public function setUp(): void
    {
        $this->service = new CustomerCategoryService();

        $this->service->addCategory(new HeavyUserCategory());
        $this->service->addCategory(new MediumUserCategory());
        $this->service->addCategory(new LightUserCategory());
        $this->service->addCategory(new NewUserCategory());
    }

    /**
     * @test
     */
    public function customerShouldBeNewUser(): void
    {
        $customer = new Customer();
        
        $returned = $this->service->getUsageCategory($customer);

        self::assertEquals(CustomerCategoryService::CATEGORY_NEW_USER, $returned);
    }

     /**
     * @test
     */
    public function customerShouldBeLightUser(): void
    {
        $customer = new Customer();
        $customer->setTotalOrders(5);
        $customer->setTotalRatings(1);
        
        $returned = $this->service->getUsageCategory($customer);

        self::assertEquals(CustomerCategoryService::CATEGORY_LIGHT_USER, $returned);
    }

     /**
     * @test
     */
    public function customerShouldBeMediumUser(): void
    {
        $customer = new Customer();
        $customer->setTotalOrders(20);
        $customer->setTotalRatings(5);
        $customer->setTotalRecommendations(1);
        
        $returned = $this->service->getUsageCategory($customer);

        self::assertEquals(CustomerCategoryService::CATEGORY_MEDIUM_USER, $returned);
    }

     /**
     * @test
     */
    public function customerShouldBeHeavyUser(): void
    {
        $customer = new Customer();
        $customer->setTotalOrders(50);
        $customer->setTotalRatings(10);
        $customer->setTotalRecommendations(5);
        
        $returned = $this->service->getUsageCategory($customer);

        self::assertEquals(CustomerCategoryService::CATEGORY_HEAVY_USER, $returned);
    }
}
