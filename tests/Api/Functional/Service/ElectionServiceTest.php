<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\DTO\ElectionVote;
use App\Api\Service\ElectionService;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(ElectionService::class)]
class ElectionServiceTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testVote(): void
    {
        /** @var ElectionService $service */
        $service = static::getContainer()->get(ElectionService::class);

        $electionVote = new ElectionVote([
            'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'dex_slug' => 'demo',
            'election_slug' => '',
            'winners_slugs' => ['bulbasaur'],
            'losers_slugs' => ['ivysaur', 'venusaur'],
        ]);
        $result = $service->vote($electionVote);

        $this->assertSame($electionVote, $result->getElectionVote());

        $pokemonsElo = $result->getPokemonsElo();

        $this->assertSame(['winners', 'losers'], array_keys($pokemonsElo));

        $this->assertSame('bulbasaur', $pokemonsElo['winners'][0]->getPokemonSlug());
        $this->assertSame(1027, $pokemonsElo['winners'][0]->getElo());

        $this->assertSame('ivysaur', $pokemonsElo['losers'][0]->getPokemonSlug());
        $this->assertSame(1003, $pokemonsElo['losers'][0]->getElo());
        $this->assertSame('venusaur', $pokemonsElo['losers'][1]->getPokemonSlug());
        $this->assertSame(1013, $pokemonsElo['losers'][1]->getElo());
    }

    public function testVoteBis(): void
    {
        /** @var ElectionService $service */
        $service = static::getContainer()->get(ElectionService::class);

        $electionVote = new ElectionVote([
            'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
            'election_slug' => 'favorite',
            'winners_slugs' => ['bulbasaur'],
            'losers_slugs' => ['ivysaur', 'venusaur'],
        ]);
        $result = $service->vote($electionVote);

        $this->assertSame($electionVote, $result->getElectionVote());

        $pokemonsElo = $result->getPokemonsElo();

        $this->assertSame(['winners', 'losers'], array_keys($pokemonsElo));

        $this->assertSame('bulbasaur', $pokemonsElo['winners'][0]->getPokemonSlug());
        $this->assertSame(1016, $pokemonsElo['winners'][0]->getElo());

        $this->assertSame('ivysaur', $pokemonsElo['losers'][0]->getPokemonSlug());
        $this->assertSame(984, $pokemonsElo['losers'][0]->getElo());
        $this->assertSame('venusaur', $pokemonsElo['losers'][1]->getPokemonSlug());
        $this->assertSame(984, $pokemonsElo['losers'][1]->getElo());
    }
}
