<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\Command\UpdateGamesAvailabilitiesCommand;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdateGamesAvailabilitiesCommand::class)]
class UpdateGamesAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountGameAvailabilityTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertGreaterThan(0, $this->getGameAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals(68970, $this->getGameAvailabilityCount());

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("68970 games' availabilities updated", $commandTester->getDisplay());
    }

    protected function getCommandName(): string
    {
        return 'app:update:games_availabilities';
    }
}
