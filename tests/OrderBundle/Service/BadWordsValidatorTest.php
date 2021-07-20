<?php

declare(strict_types=1);

namespace Tests\OrderBundle\Service;

use Mockery;
use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

final class BadWordsValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider badWordsProvider
     */
    public function hasBadWords($badWordsList, $text, $expected): void
    {
        $mock = $this->createMock(BadWordsRepository::class);
        $mock->method('findAllAsArray')->willReturn($badWordsList);

        $validator = new BadWordsValidator($mock);

        $returned = $validator->hasBadWords($text);

        self::assertEquals($expected, $returned);
    }

    /**
     * @test
     * @dataProvider badWordsProvider
     */
    public function hasBadWordsUsangeMockely($badWordsList, $text, $expected): void
    {
        $mock = Mockery::mock(BadWordsRepository::class);
        $mock->shouldReceive('findAllAsArray')->andReturn($badWordsList);

        $validator = new BadWordsValidator($mock);

        $returned = $validator->hasBadWords($text);

        self::assertEquals($expected, $returned);
    }

    public function badWordsProvider(): array
    {
        $badWordsListByTigre = ['jujuba de anis', 'vai comer beijinho antes da janta', 'cajuzinho diet'];

        return [
            'shouldFindWhenHasBadWords' => [
                'badWordsList' => $badWordsListByTigre,
                'text' => 'Pô Hamilton, seu jujuba de anis...',
                'expected' => true
            ],
            'shouldNotFindWhenHasNotBadWords' => [
                'badWordsList' => $badWordsListByTigre,
                'text' => 'Agora o chefe vai ficar uma pelúcia com a gente',
                'expected' => false
            ],
            'shouldNotFindWhenTextIsEmpty' => [
                'badWordsList' => $badWordsListByTigre,
                'text' => '',
                'expected' => false
            ],
            'shouldNotFindWhenBadWordsListIsEmpty' => [
                'badWordsList' => [],
                'text' => 'Seu cajuzinho diet',
                'expected' => false
            ]
        ];
    }
}
