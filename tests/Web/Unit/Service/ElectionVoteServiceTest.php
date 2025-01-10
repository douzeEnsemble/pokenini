<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service;

use App\Web\DTO\ElectionVote;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ElectionVoteApiService;
use App\Web\Service\ElectionVoteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ElectionVoteService::class)]
class ElectionVoteServiceTest extends TestCase
{
    public function testVote(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('8800088')
        ;

        $electionVote = new ElectionVote([
            'dex_slug' => 'demo',
            'election_slug' => 'whatever',
            'winners_slugs' => ['pichu'],
            'losers_slugs' => ['pikachu', 'raichu'],
        ]);

        $apiService = $this->createMock(ElectionVoteApiService::class);
        $apiService
            ->expects($this->once())
            ->method('vote')
            ->with(
                '8800088',
                $electionVote,
            )
            ->willReturn(['voteCount' => 2])
        ;

        $service = new ElectionVoteService($userTokenService, $apiService);
        $result = $service->vote($electionVote);

        $this->assertSame(2, $result->getVoteCount());
    }

    public function testVoteWinnerAsLoser(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('8800088')
        ;

        $electionVote = new ElectionVote([
            'dex_slug' => 'demo',
            'election_slug' => 'whatever',
            'winners_slugs' => ['pichu'],
            'losers_slugs' => ['pikachu', 'pichu', 'raichu'],
        ]);

        $apiService = $this->createMock(ElectionVoteApiService::class);
        $apiService
            ->expects($this->once())
            ->method('vote')
            ->with(
                '8800088',
                $electionVote,
            )
            ->willReturn(['voteCount' => 3])
        ;

        $service = new ElectionVoteService($userTokenService, $apiService);
        $result = $service->vote($electionVote);

        $this->assertSame(3, $result->getVoteCount());
    }
}
