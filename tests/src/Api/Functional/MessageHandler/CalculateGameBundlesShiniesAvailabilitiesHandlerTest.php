<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\MessageHandler;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\MessageHandler\CalculateGameBundlesShiniesAvailabilitiesHandler;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CounterTableTrait;
use App\Tests\Api\Common\Traits\GetterTrait\GetActionLogTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @internal
 */
#[CoversClass(CalculateGameBundlesShiniesAvailabilitiesHandler::class)]
#[CoversTrait(ActionEnderTrait::class)]
class CalculateGameBundlesShiniesAvailabilitiesHandlerTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use InteractsWithMessenger;
    use CounterTableTrait;
    use CountActionLogTrait;
    use GetActionLogTrait;

    #[\Override]
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testHandler(): void
    {
        $transport = $this->transport('async');
        $transport->throwExceptions();

        $this->assertEquals(32, $this->getTableCount('game_bundle_shiny_availability'));

        $beforeTotalCount = $this->getActionLogCount();
        $beforeToProcessCount = $this->getActionLogToProcessCount();
        $beforeDoneCount = $this->getActionLogDoneCount();

        $transport->send(
            new CalculateGameBundlesShiniesAvailabilities(
                $this->getIdToProcess(CalculateGameBundlesShiniesAvailabilities::class)
            )
        );

        $transport->queue()->assertContains(CalculateGameBundlesShiniesAvailabilities::class, 1);

        $transport->process(1);

        $transport->queue()->assertEmpty();

        $this->assertEquals(9, $this->getTableCount('game_bundle_shiny_availability'));

        $this->assertEquals($beforeTotalCount + 1, $this->getActionLogCount());
        $this->assertEquals($beforeToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($beforeDoneCount + 1, $this->getActionLogDoneCount());
    }

    public function testExceptionHandler(): void
    {
        $transport = $this->transport('async');
        $transport->throwExceptions();

        $transport->send(new CalculateGameBundlesShiniesAvailabilities('0a35b132-fa1d-4528-b866-dadac5876e1c'));

        $transport->queue()->assertContains(CalculateGameBundlesShiniesAvailabilities::class, 1);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Can't find ActionLog #0a35b132-fa1d-4528-b866-dadac5876e1c");

        $transport->process(1);
    }
}
