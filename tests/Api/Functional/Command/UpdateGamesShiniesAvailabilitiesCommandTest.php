<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\Command\UpdateGamesShiniesAvailabilitiesCommand;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameShinyAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdateGamesShiniesAvailabilitiesCommand::class)]
class UpdateGamesShiniesAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountGameShinyAvailabilityTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertGreaterThan(0, $this->getGameShinyAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals(4598, $this->getGameShinyAvailabilityCount());

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("4598 games' shinies' availabilities updated", $commandTester->getDisplay());
    }

    protected function getCommandName(): string
    {
        return 'app:update:games_shinies_availabilities';
    }
}
