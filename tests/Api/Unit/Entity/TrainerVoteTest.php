<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\TrainerVote;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TrainerVote::class)]
class TrainerVoteTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $entity = new TrainerVote();

        $this->assertInstanceOf(\DateTimeImmutable::class, $entity->getCreatedAt());
    }
}
