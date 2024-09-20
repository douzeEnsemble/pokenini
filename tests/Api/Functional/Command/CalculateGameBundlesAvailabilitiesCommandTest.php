<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\Command\CalculateGameBundlesAvailabilitiesCommand;
use App\Api\Repository\GamesAvailabilitiesRepository;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameAvailabilityTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CalculateGameBundlesAvailabilitiesCommand::class)]
class CalculateGameBundlesAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountGameAvailabilityTrait;
    use CountGameBundleAvailabilityTrait;
    use CountActionLogTrait;

    public function testNoGamesAvailabilities(): void
    {
        /** @var GamesAvailabilitiesRepository $repo */
        $repo = static::getContainer()->get(GamesAvailabilitiesRepository::class);
        $repo->removeAll();

        $this->assertEquals(0, $this->getGameAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("0 bundles' availabilities calculated", $commandTester->getDisplay());
    }

    public function testCalculateBundlesAvailabilities(): void
    {
        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("11 bundles' availabilities calculated", $commandTester->getDisplay());
    }

    protected function getCommandName(): string
    {
        return 'app:calculate:game_bundles_availabilities';
    }
}
