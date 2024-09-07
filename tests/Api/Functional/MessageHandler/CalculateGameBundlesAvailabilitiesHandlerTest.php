<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\MessageHandler;

use App\Api\Message\CalculateGameBundlesAvailabilities;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CounterTableTrait;
use App\Tests\Api\Common\Traits\GetterTrait\GetActionLogTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @internal
 *
 * @coversNothing
 */
class CalculateGameBundlesAvailabilitiesHandlerTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use InteractsWithMessenger;
    use CounterTableTrait;
    use CountActionLogTrait;
    use GetActionLogTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testHandler(): void
    {
        $transport = $this->transport('async');
        $transport->throwExceptions();

        $this->assertEquals(34, $this->getTableCount('game_bundle_availability'));

        $beforeTotalCount = $this->getActionLogCount();
        $beforeToProcessCount = $this->getActionLogToProcessCount();
        $beforeDoneCount = $this->getActionLogDoneCount();

        $transport->send(
            new CalculateGameBundlesAvailabilities(
                $this->getIdToProcess(CalculateGameBundlesAvailabilities::class)
            )
        );

        $transport->queue()->assertContains(CalculateGameBundlesAvailabilities::class, 1);

        $transport->process(1);

        $transport->queue()->assertEmpty();

        $this->assertEquals(11, $this->getTableCount('game_bundle_availability'));

        $this->assertEquals($beforeTotalCount + 1, $this->getActionLogCount());
        $this->assertEquals($beforeToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($beforeDoneCount + 1, $this->getActionLogDoneCount());
    }

    public function testExceptionHandler(): void
    {
        $transport = $this->transport('async');
        $transport->throwExceptions();

        $transport->send(new CalculateGameBundlesAvailabilities('0a35b132-fa1d-4528-b866-dadac5876e1c'));

        $transport->queue()->assertContains(CalculateGameBundlesAvailabilities::class, 1);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Can't find ActionLog #0a35b132-fa1d-4528-b866-dadac5876e1c");

        $transport->process(1);
    }
}
