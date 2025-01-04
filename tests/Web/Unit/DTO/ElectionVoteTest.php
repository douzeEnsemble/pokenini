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
        $object = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);

        $this->assertSame('douze', $object->electionSlug);
        $this->assertSame(['pikachu'], $object->winnersSlugs);
        $this->assertSame(['pichu', 'raichu'], $object->losersSlugs);
    }

    public function testWinnerAsLoser(): void
    {
        $object = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'pikachu', 'raichu'],
        ]);

        $this->assertSame('douze', $object->electionSlug);
        $this->assertSame(['pikachu'], $object->winnersSlugs);
        $this->assertSame(['pichu', 'raichu'], $object->losersSlugs);
    }

    public function testWinnersAsLosers(): void
    {
        $object = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu', 'pichu'],
            'losers_slugs' => ['pichu', 'pikachu', 'raichu'],
        ]);

        $this->assertSame('douze', $object->electionSlug);
        $this->assertSame(['pikachu', 'pichu'], $object->winnersSlugs);
        $this->assertSame(['raichu'], $object->losersSlugs);
    }

    public function testWithEmptyWinners(): void
    {
        $object = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pichu', ''],
            'losers_slugs' => ['pikachu', 'raichu'],
        ]);

        $this->assertSame('douze', $object->electionSlug);
        $this->assertSame(['pichu'], $object->winnersSlugs);
        $this->assertSame(['pikachu', 'raichu'], $object->losersSlugs);
    }

    public function testWithEmptyLosers(): void
    {
        $object = new ElectionVote([
            'election_slug' => 'douze',
            'winners_slugs' => ['pichu'],
            'losers_slugs' => ['pikachu', 'raichu', ''],
        ]);

        $this->assertSame('douze', $object->electionSlug);
        $this->assertSame(['pichu'], $object->winnersSlugs);
        $this->assertSame(['pikachu', 'raichu'], $object->losersSlugs);
    }

    public function testMissingElectionSlug(): void
    {
        $object = new ElectionVote([
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);

        $this->assertSame('', $object->electionSlug);
        $this->assertSame(['pikachu'], $object->winnersSlugs);
        $this->assertSame(['pichu', 'raichu'], $object->losersSlugs);
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
