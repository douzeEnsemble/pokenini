<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\TrainerVoteRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountTrainerVoteTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(TrainerVoteRepository::class)]
class TrainerVoteRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountTrainerVoteTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testRegister(): void
    {
        /** @var TrainerVoteRepository $repo */
        $repo = static::getContainer()->get(TrainerVoteRepository::class);

        $repo->register(
            '7b52009b64fd0a2a49e6d8a939753077792b0554',
            '',
            [
                'butterfree',
                'butterfree-f',
            ],
            [
                'caterpie',
                'metapod',
            ],
        );

        $this->assertSame(
            3,
            $this->getTrainerVoteCount(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                ''
            )
        );
    }

    public function testGetCount(): void
    {
        /** @var TrainerVoteRepository $repo */
        $repo = static::getContainer()->get(TrainerVoteRepository::class);

        $this->assertSame(
            2,
            $repo->getCount(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                ''
            )
        );

        $this->assertSame(
            3,
            $repo->getCount(
                '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'favorite'
            )
        );
    }
}
