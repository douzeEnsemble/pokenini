<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\DTO\ElectionVote;
use App\Web\Service\Api\ElectionVoteApiService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

        $this
            ->getService([
                'trainer_external_id' => '5465465',
                'election_slug' => 'whatever',
                'winners_slugs' => ['pichu'],
                'losers_slugs' => ['pikachu', 'raichu'],
            ])
            ->vote(
                '5465465',
                $electionVote,
            )
        ;

        $this->assertEmpty($this->cache->getValues());
    }

    /**
     * @param string[]|string[][] $body
     */
    private function getService(
        array $body
    ): ElectionVoteApiService {
        $client = $this->createMock(HttpClientInterface::class);

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
                    'body' => $body,
                ],
            )
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
