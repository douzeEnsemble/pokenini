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
                'winner_slug' => 'bulbasaur',
                'losers_slugs' => ['ivysaur', 'venusaur'],
            ])
        );

        $this->assertCount(2, $results);

        $this->assertSame('bulbasaur', $results[0]->getWinnerSlug());
        $this->assertSame(1027, $results[0]->getWinnerElo());
        $this->assertSame('ivysaur', $results[0]->getLoserSlug());
        $this->assertSame(1003, $results[0]->getLoserElo());

        $this->assertSame('bulbasaur', $results[1]->getWinnerSlug());
        $this->assertSame(1027, $results[1]->getWinnerElo());
        $this->assertSame('venusaur', $results[1]->getLoserSlug());
        $this->assertSame(1013, $results[1]->getLoserElo());
    }
}
