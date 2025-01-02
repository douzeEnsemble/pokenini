<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\ElectionVote;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

/**
 * @internal
 */
#[CoversClass(ElectionVote::class)]
class ElectionVoteTest extends TestCase
{
    public function testOk(): void
    {
        $attributes = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);

        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(['pikachu'], $attributes->winnersSlugs);
        $this->assertSame(['pichu', 'raichu'], $attributes->losersSlugs);
    }

    public function testWinnerAsLoser(): void
    {
        $attributes = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'pikachu', 'raichu'],
        ]);

        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(['pikachu'], $attributes->winnersSlugs);
        $this->assertSame(['pichu', 'raichu'], $attributes->losersSlugs);
    }

    public function testWinnersAsLosers(): void
    {
        $attributes = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu', 'pichu'],
            'losers_slugs' => ['pichu', 'pikachu', 'raichu'],
        ]);

        $this->assertSame('douze', $attributes->electionSlug);
        $this->assertSame(['pikachu', 'pichu'], $attributes->winnersSlugs);
        $this->assertSame(['raichu'], $attributes->losersSlugs);
    }

    public function testMissingElectionSlug(): void
    {
        $attributes = new ElectionVote([
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);

        $this->assertSame('', $attributes->electionSlug);
        $this->assertSame(['pikachu'], $attributes->winnersSlugs);
        $this->assertSame(['pichu', 'raichu'], $attributes->losersSlugs);
    }

    public function testWrongValueForElectionSlug(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionVote([
            'election_slug' => false,
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);
    }

    public function testWrongValueForWinnerSlug(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionVote([
            'winners_slugs' => [54654],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);
    }

    public function testWrongValueForLosersSlugs(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ElectionVote([
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => 'pichu',
        ]);
    }

    public function testAnotherValue(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
            'other' => 'idk',
        ]);
    }
}
