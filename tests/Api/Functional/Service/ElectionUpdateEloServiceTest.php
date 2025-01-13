<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\DTO\ElectionVote;
use App\Api\Service\ElectionUpdateEloService;
use App\Tests\Api\Common\Traits\GetterTrait\GetTrainerPokemonEloTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(ElectionUpdateEloService::class)]
class ElectionUpdateEloServiceTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use GetTrainerPokemonEloTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testVote(): void
    {
        /** @var ElectionUpdateEloService $service */
        $service = static::getContainer()->get(ElectionUpdateEloService::class);

        $results = $service->update(
            new ElectionVote([
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'demo',
                'election_slug' => '',
                'winners_slugs' => ['bulbasaur'],
                'losers_slugs' => ['ivysaur', 'venusaur'],
            ])
        );

        $this->assertCount(2, $results);
        $this->assertSame(['winners', 'losers'], array_keys($results));

        $this->assertSame('bulbasaur', $results['winners'][0]->getPokemonSlug());
        $this->assertSame(1027, $results['winners'][0]->getElo());
        $this->assertSame(
            [
                'elo' => 1027,
                'count' => 1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'bulbasaur'),
        );

        $this->assertSame('ivysaur', $results['losers'][0]->getPokemonSlug());
        $this->assertSame(1003, $results['losers'][0]->getElo());
        $this->assertSame(
            [
                'elo' => 1003,
                'count' => -1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'ivysaur'),
        );

        $this->assertSame('venusaur', $results['losers'][1]->getPokemonSlug());
        $this->assertSame(1013, $results['losers'][1]->getElo());
        $this->assertSame(
            [
                'elo' => 1013,
                'count' => -1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', '', 'venusaur'),
        );
    }

    public function testVoteBis(): void
    {
        /** @var ElectionUpdateEloService $service */
        $service = static::getContainer()->get(ElectionUpdateEloService::class);

        $results = $service->update(
            new ElectionVote([
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'demo',
                'election_slug' => 'favorite',
                'winners_slugs' => ['bulbasaur'],
                'losers_slugs' => ['ivysaur', 'venusaur', 'venusaur-f'],
            ])
        );

        $this->assertCount(2, $results);
        $this->assertSame(['winners', 'losers'], array_keys($results));

        $this->assertSame('bulbasaur', $results['winners'][0]->getPokemonSlug());
        $this->assertSame(1016, $results['winners'][0]->getElo());
        $this->assertSame(
            [
                'elo' => 1016,
                'count' => 1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', 'favorite', 'bulbasaur'),
        );

        $this->assertSame('ivysaur', $results['losers'][0]->getPokemonSlug());
        $this->assertSame(984, $results['losers'][0]->getElo());
        $this->assertSame(
            [
                'elo' => 984,
                'count' => -1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', 'favorite', 'ivysaur'),
        );

        $this->assertSame('venusaur', $results['losers'][1]->getPokemonSlug());
        $this->assertSame(984, $results['losers'][1]->getElo());
        $this->assertSame(
            [
                'elo' => 984,
                'count' => -1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', 'favorite', 'venusaur'),
        );

        $this->assertSame('venusaur-f', $results['losers'][2]->getPokemonSlug());
        $this->assertSame(984, $results['losers'][2]->getElo());
        $this->assertSame(
            [
                'elo' => 984,
                'count' => -1,
            ],
            $this->getEloAndCount('7b52009b64fd0a2a49e6d8a939753077792b0554', 'demo', 'favorite', 'venusaur-f'),
        );
    }

    public function testVoteNoWinners(): void
    {
        /** @var ElectionUpdateEloService $service */
        $service = static::getContainer()->get(ElectionUpdateEloService::class);

        $results = $service->update(
            new ElectionVote([
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'demo',
                'election_slug' => '',
                'winners_slugs' => [],
                'losers_slugs' => ['ivysaur', 'venusaur'],
            ])
        );

        $this->assertEmpty($results);
    }

    public function testVoteNoLosers(): void
    {
        /** @var ElectionUpdateEloService $service */
        $service = static::getContainer()->get(ElectionUpdateEloService::class);

        $results = $service->update(
            new ElectionVote([
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'dex_slug' => 'demo',
                'election_slug' => '',
                'winners_slugs' => ['ivysaur', 'venusaur'],
                'losers_slugs' => [],
            ])
        );

        $this->assertEmpty($results);
    }
}
