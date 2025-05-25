<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\AbstractActionStarter;
use App\Api\ActionStarter\UpdateCollectionsAvailabilitiesActionStarter;
use App\Api\Command\AbstractUpdateCommand;
use App\Api\Command\UpdateCollectionsAvailabilitiesCommand;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateCollectionsAvailabilities;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountCollectionAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdateCollectionsAvailabilitiesCommand::class)]
#[CoversClass(AbstractUpdateCommand::class)]
#[CoversClass(UpdateCollectionsAvailabilitiesActionStarter::class)]
#[CoversClass(AbstractActionStarter::class)]
#[CoversClass(UpdateCollectionsAvailabilities::class)]
#[CoversClass(AbstractActionMessage::class)]
#[CoversClass(ActionEnderTrait::class)]
class UpdateCollectionsAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountCollectionAvailabilityTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertGreaterThan(0, $this->getCollectionAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals(968, $this->getCollectionAvailabilityCount());

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("968 collections' availabilities updated", $commandTester->getDisplay());
    }

    #[\Override]
    protected function getCommandName(): string
    {
        return 'app:update:collections_availabilities';
    }
}
