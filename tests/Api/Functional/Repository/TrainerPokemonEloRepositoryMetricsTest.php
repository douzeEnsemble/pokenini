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
                'max_view' => 0,
                'max_view_count' => 6,
                'under_max_view_count' => 0,
                'elo_count' => 6,
            ],
            $metrics,
        );
    }

    public function testGetMetricsBis(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $metrics = $repo->getMetrics('7b52009b64fd0a2a49e6d8a939753077792b0554', 'redgreenblueyellow', 'affinee');

        $this->assertSame(
            [
                'max_view' => 3,
                'max_view_count' => 1,
                'under_max_view_count' => 1,
                'elo_count' => 6,
            ],
            $metrics,
        );
    }

    public function testGetMetricsNo(): void
    {
        /** @var TrainerPokemonEloRepository $repo */
        $repo = static::getContainer()->get(TrainerPokemonEloRepository::class);

        $metrics = $repo->getMetrics('7b52009b64fd0a2a49e6d8a939753077792b0554', 'redgreenblueyellow', 'doesntexists');

        $this->assertSame(
            [
                'max_view' => 0,
                'max_view_count' => 0,
                'under_max_view_count' => 0,
                'elo_count' => 0,
            ],
            $metrics,
        );
    }
}
