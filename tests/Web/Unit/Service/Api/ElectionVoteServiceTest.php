<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\ElectionVoteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @internal
 */
#[CoversClass(ElectionVoteService::class)]
class ElectionVoteServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testVote(): void
    {
        $this
            ->getService([
                'trainer_external_id' => '5465465',
                'election_slug' => 'whatever',
                'winner_slug' => 'pichu',
                'losers_slugs' => ['pikachu', 'raichu'],
            ])
            ->vote(
                '5465465',
                'whatever',
                'pichu',
                ['pikachu', 'raichu'],
            )
        ;

        $this->assertEmpty($this->cache->getValues());
    }

    /**
     * @param string[]|string[][] $body
     */
    private function getService(
        array $body
    ): ElectionVoteService {
        $client = $this->createMock(HttpClientInterface::class);

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://api.domain/favorite/vote',
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

        return new ElectionVoteService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
