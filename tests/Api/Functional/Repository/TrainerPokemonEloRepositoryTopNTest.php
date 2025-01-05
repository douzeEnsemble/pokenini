<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\TrainerPokemonEloRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(TrainerPokemonEloRepository::class)]
class TrainerPokemonEloRepositoryTopNTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testTop5(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $list = $repo->getTopN('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 5);

        $this->assertCount(
            5,
            $list,
        );

        $this->assertAllKeysMatches(
            $list,
            'elo',
            [
                1060,
                1050,
                1040,
                1030,
                1020,
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'percent',
            [
                '28.57',
                '28.57',
                '28.57',
                '28.57',
                '28.57',
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'adjusted_threshold',
            [
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'detachment_threshold',
            [
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
            ],
        );
    }

    public function testTop5Home(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $list = $repo->getTopN('7b52009b64fd0a2a49e6d8a939753077792b0554', 'home', '', 5);

        $this->assertCount(
            0,
            $list,
        );
    }

    public function testTop5HomeFavorite(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $list = $repo->getTopN('7b52009b64fd0a2a49e6d8a939753077792b0554', 'home', 'favorite', 5);

        $this->assertCount(
            5,
            $list,
        );

        $this->assertAllKeysMatches(
            $list,
            'elo',
            [
                1000,
                1000,
                1000,
                1000,
                1000,
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'percent',
            [
                '27.27',
                '27.27',
                '27.27',
                '27.27',
                '27.27',
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'adjusted_threshold',
            [
                '1000.0000000000000000',
                '1000.0000000000000000',
                '1000.0000000000000000',
                '1000.0000000000000000',
                '1000.0000000000000000',
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'detachment_threshold',
            [
                '1000.0000000000000000',
                '1000.0000000000000000',
                '1000.0000000000000000',
                '1000.0000000000000000',
                '1000.0000000000000000',
            ],
        );
    }

    public function testTop10(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $list = $repo->getTopN('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 10);

        $this->assertCount(
            6,
            $list,
        );

        $this->assertAllKeysMatches(
            $list,
            'elo',
            [
                1060,
                1050,
                1040,
                1030,
                1020,
                1010,
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'percent',
            [
                '28.57',
                '28.57',
                '28.57',
                '28.57',
                '28.57',
                '28.57',
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'adjusted_threshold',
            [
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
                '1071.106993782368534317',
            ],
        );
        $this->assertAllKeysMatches(
            $list,
            'detachment_threshold',
            [
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
                '1091.1248608016091207',
            ],
        );
    }

    /**
     * @param float[][]|int[][]|string[][] $list
     * @param float[]|int[]|string[]       $matches
     */
    private function assertAllKeysMatches(array $list, string $key, array $matches): void
    {
        $this->assertSame(
            $matches,
            array_map(
                fn ($value) => $value[$key],
                $list,
            ),
        );
    }
}
