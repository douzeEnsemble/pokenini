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
class TrainerPokemonEloRepositoryMetricsTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetMetrics(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $metrics = $repo->getMetrics('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '');

        $this->assertSame(
            [
                'avg_elo' => 1035.0,
                'stddev_elo' => 18.708286933869708,
                'count_elo' => 6,
            ],
            $metrics,
        );
    }

    public function testGetMetricsBis(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $metrics = $repo->getMetrics('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', 'favorite');

        $this->assertSame(
            [
                'avg_elo' => 1000.0,
                'stddev_elo' => 0.0,
                'count_elo' => 6,
            ],
            $metrics,
        );
    }
}
