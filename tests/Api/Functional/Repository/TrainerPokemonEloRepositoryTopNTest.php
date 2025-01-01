<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\TrainerPokemonEloRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('providerTopN')]
    public function testTopN(int $count, int $expectedCount): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $list = $repo->getTopN('7b52009b64fd0a2a49e6d8a939753077792b0554', '', $count);

        $this->assertCount(
            $expectedCount,
            $list,
        );

        $scores = array_map(
            fn ($value) => $value['elo'],
            $list
        );

        $sortedScores = $scores;
        arsort($sortedScores);

        $this->assertSame($scores, $sortedScores);
    }

    /**
     * @return int[][]
     */
    public static function providerTopN(): array
    {
        return [
            '5' => [
                'count' => 5,
                'expectedCount' => 5,
            ],
            '3' => [
                'count' => 3,
                'expectedCount' => 3,
            ],
            '10' => [
                'count' => 10,
                'expectedCount' => 6,
            ],
        ];
    }
}
