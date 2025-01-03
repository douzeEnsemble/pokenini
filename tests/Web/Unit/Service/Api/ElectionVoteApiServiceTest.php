<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\DTO\ElectionVote;
use App\Web\Service\Api\ElectionVoteApiService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(ElectionVoteApiService::class)]
class ElectionVoteApiServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testVote(): void
    {
        $electionVote = new ElectionVote([
            'election_slug' => 'whatever',
            'winners_slugs' => ['pichu'],
            'losers_slugs' => ['pikachu', 'raichu'],
        ]);

        $result = $this
            ->getService('5465465', 'whatever', ['pichu'], ['pikachu', 'raichu'])
            ->vote(
                '5465465',
                $electionVote,
            )
        ;

        $this->assertEmpty($this->cache->getValues());

        $this->assertSame(3, $result['voteCount']);
    }

    /**
     * @param string[] $winnersSlugs
     * @param string[] $losersSlugs
     */
    private function getService(
        string $trainerId,
        string $electionSlug,
        array $winnersSlugs,
        array $losersSlugs,
    ): ElectionVoteApiService {
        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents("/var/www/html/tests/resources/Web/unit/service/api/election_vote_{$trainerId}_{$electionSlug}.json");

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getContent')
            ->willReturn($json)
        ;

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://api.domain/election/vote',
                [
                    'headers' => [
                        'accept' => 'application/json',
                    ],
                    'auth_basic' => [
                        'web',
                        'douze',
                    ],
                    'body' => [
                        'trainer_external_id' => $trainerId,
                        'election_slug' => $electionSlug,
                        'winners_slugs' => $winnersSlugs,
                        'losers_slugs' => $losersSlugs,
                    ],
                ],
            )
            ->willReturn($response)
        ;

        $this->cache = new ArrayAdapter();

        return new ElectionVoteApiService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
