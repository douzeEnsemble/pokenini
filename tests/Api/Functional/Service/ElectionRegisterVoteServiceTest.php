<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\DTO\ElectionVote;
use App\Api\Service\ElectionRegisterVoteService;
use App\Tests\Api\Common\Traits\CounterTrait\CountTrainerVoteTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(ElectionRegisterVoteService::class)]
class ElectionRegisterVoteServiceTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountTrainerVoteTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testRegister(): void
    {
        /** @var ElectionRegisterVoteService $service */
        $service = static::getContainer()->get(ElectionRegisterVoteService::class);

        $service->register(
            new ElectionVote([
                'trainer_external_id' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'election_slug' => '',
                'winners_slugs' => ['bulbasaur'],
                'losers_slugs' => ['ivysaur', 'venusaur'],
            ])
        );

        $this->assertSame(3, $this->getTrainerVoteCount('7b52009b64fd0a2a49e6d8a939753077792b0554', ''));
    }
}
