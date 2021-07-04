<?php

declare(strict_types=1);

namespace Tests\PaymentBundle\Service;

use DateTime;
use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

final class GatewayTest extends TestCase
{
    private const CREDIT_CARD_MAGIC = 9999999999999999;
    private const VALID_PASSWORD_MAGIC = 'valid-password';

    /**
     * @test
     */
    public function shouldNotPayWhenAuthenticationFail()
    {
        $clientMock = $this->createMock(HttpClientInterface::class);
        $clientMock->method('send')
            ->will($this->returnCallback(
                function ($method, $url, $body) {
                    return $this->fakeHttpClientSend($method, $url, $body);
                }
            )); // fake here

        $loggerMock = $this->createMock(LoggerInterface::class);
        $user = 'dominic.toretto';
        $password = '10secondcar';

        $gateway = new Gateway($clientMock, $loggerMock, $user, $password);

        $creditCard = 6551416128011630;
        $value = 100;

        $returned = $gateway->pay(
            'Dominic Toretto',
            $creditCard,
            $this->getDateTimeNow(),
            $value
        );

        $this->assertFalse($returned);
    }

    /**
     * @test
     */
    public function shouldNotPayWhenFailOnGateway()
    {
        $clientMock = $this->createMock(HttpClientInterface::class);
        $clientMock->method('send')
            ->will($this->returnCallback(
                function ($method, $url, $body) {
                    return $this->fakeHttpClientSend($method, $url, $body);
                }
            ));

        $loggerMock = $this->createMock(LoggerInterface::class);
        $user = 'brian.oconner';
        $password = self::VALID_PASSWORD_MAGIC;

        $gateway = new Gateway($clientMock, $loggerMock, $user, $password);

        $creditCard = 4389356885564285;
        $value = 200;

        $returned = $gateway->pay(
            "Brian O'Conner",
            $creditCard,
            $this->getDateTimeNow(),
            $value
        );

        $this->assertFalse($returned);
    }

    /**
     * @test
     */
    public function shouldSuccessfullyPayWhenGatewayReturnOk()
    {
        $clientMock = $this->createMock(HttpClientInterface::class);
        $clientMock->method('send')
            ->will($this->returnCallback(
                function ($method, $url, $body) {
                    return $this->fakeHttpClientSend($method, $url, $body);
                }
            ));

        $loggerMock = $this->createMock(LoggerInterface::class);
        $user = 'roman.pearce';
        $password = self::VALID_PASSWORD_MAGIC;

        $gateway = new Gateway($clientMock, $loggerMock, $user, $password);

        $creditCard = self::CREDIT_CARD_MAGIC;
        $value = 300;

        $returned = $gateway->pay(
            'Roman Pearce',
            $creditCard,
            $this->getDateTimeNow(),
            $value
        );

        $this->assertTrue($returned);
    }

    private function fakeHttpClientSend($method, $url, $body)
    {
        switch ($url) {
            case Gateway::BASE_URL . '/authenticate':
                
                if ($body['password'] === self::VALID_PASSWORD_MAGIC) {
                    return 'token-valido';
                }

                return null;
                break;
            case Gateway::BASE_URL . '/pay':

                if ($body['credit_card_number'] === self::CREDIT_CARD_MAGIC) {
                    return ['paid' => true];
                }

                return ['paid' => false];
                break;
        }
    }

    private function getDateTimeNow()
    {
        return new DateTime('now');
    }
}
