<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\AbstractActionStarter;
use App\Api\ActionStarter\CalculateGameBundlesShiniesAvailabilitiesActionStarter;
use App\Api\Command\AbstractCalculateCommand;
use App\Api\Command\CalculateGameBundlesShiniesAvailabilitiesCommand;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateGameBundlesShiniesAvailabilities;
use App\Api\MessageHandler\CalculateGameBundlesShiniesAvailabilitiesHandler;
use App\Api\Repository\GamesShiniesAvailabilitiesRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleShinyAvailabilityTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameShinyAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;

/**
 * @internal
 */
#[CoversClass(CalculateGameBundlesShiniesAvailabilitiesCommand::class)]
#[CoversClass(AbstractCalculateCommand::class)]
#[CoversClass(CalculateGameBundlesShiniesAvailabilitiesActionStarter::class)]
#[CoversClass(AbstractActionStarter::class)]
#[CoversClass(CalculateGameBundlesShiniesAvailabilities::class)]
#[CoversClass(AbstractActionMessage::class)]
#[CoversClass(CalculateGameBundlesShiniesAvailabilitiesHandler::class)]
#[CoversTrait(ActionEnderTrait::class)]
class CalculateGameBundlesShiniesAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountGameShinyAvailabilityTrait;
    use CountGameBundleShinyAvailabilityTrait;
    use CountActionLogTrait;

    public function testNoGamesShiniesAvailabilities(): void
    {
        /** @var GamesShiniesAvailabilitiesRepository $repo */
        $repo = static::getContainer()->get(GamesShiniesAvailabilitiesRepository::class);
        $repo->removeAll();

        $this->assertEquals(0, $this->getGameShinyAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString(
            "0 bundles' shinies' availabilities calculated",
            $commandTester->getDisplay()
        );
    }

    public function testCalculateBundlesShiniesAvailabilities(): void
    {
        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString(
            "9 bundles' shinies' availabilities calculated",
            $commandTester->getDisplay()
        );
    }

    protected function getCommandName(): string
    {
        return 'app:calculate:game_bundles_shinies_availabilities';
    }
}
