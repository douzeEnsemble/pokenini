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

    public function testUpdateElo(): void
    {
        /** @var ElectionService $service */
        $service = static::getContainer()->get(ElectionService::class);

        $results = $service->update(
            new ElectionVote([
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'election_slug' => '',
                'winners_slugs' => ['bulbasaur'],
                'losers_slugs' => ['ivysaur', 'venusaur'],
            ])
        );

        $this->assertCount(2, $results);
        $this->assertSame(['winners', 'losers'], array_keys($results));

        $this->assertSame('bulbasaur', $results['winners'][0]->getPokemonSlug());
        $this->assertSame(1027, $results['winners'][0]->getElo());

        $this->assertSame('ivysaur', $results['losers'][0]->getPokemonSlug());
        $this->assertSame(1003, $results['losers'][0]->getElo());
        $this->assertSame('venusaur', $results['losers'][1]->getPokemonSlug());
        $this->assertSame(1013, $results['losers'][1]->getElo());
    }
}
