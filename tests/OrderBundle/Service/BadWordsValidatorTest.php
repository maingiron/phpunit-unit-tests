<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

final class BadWordsValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider badWordsProvider
     */
    public function hasBadWords($badWordsList, $text, $expected)
    {
        $mock = $this->createMock(BadWordsRepository::class);
        $mock->method('findAllAsArray')->willReturn($badWordsList);

        $validator = new BadWordsValidator($mock);

        $returned = $validator->hasBadWords($text);

        self::assertEquals($expected, $returned);
    }

    public function badWordsProvider()
    {
        $badWordsList = ['bobo', 'chule', 'besta'];

        return [
            'shouldFindWhenHasBadWords' => [
                'badWordsList' => $badWordsList,
                'text' => 'Restaurante de gente besta',
                'expected' => true
            ],
            'shouldNotFindWhenHasNotBadWords' => [
                'badWordsList' => $badWordsList,
                'text' => 'Trocar batata por salada',
                'expected' => false
            ],
            'shouldNotFindWhenTextIsEmpty' => [
                'badWordsList' => $badWordsList,
                'text' => '',
                'expected' => false
            ],
            'shouldNotFindWhenBadWordsListIsEmpty' => [
                'badWordsList' => [],
                'text' => 'Restaurante de gente besta',
                'expected' => false
            ]
        ];
    }
}
