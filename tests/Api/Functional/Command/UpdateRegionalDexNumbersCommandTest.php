<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\AbstractActionStarter;
use App\Api\ActionStarter\UpdateRegionalDexNumbersActionStarter;
use App\Api\Command\AbstractUpdateCommand;
use App\Api\Command\UpdateRegionalDexNumbersCommand;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateRegionalDexNumbers;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountRegionalDexNumberTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;

/**
 * @internal
 */
#[CoversClass(UpdateRegionalDexNumbersCommand::class)]
#[CoversClass(AbstractUpdateCommand::class)]
#[CoversClass(UpdateRegionalDexNumbersActionStarter::class)]
#[CoversClass(AbstractActionStarter::class)]
#[CoversClass(UpdateRegionalDexNumbers::class)]
#[CoversClass(AbstractActionMessage::class)]
#[CoversTrait(ActionEnderTrait::class)]
class UpdateRegionalDexNumbersCommandTest extends AbstractTestCaseCommand
{
    use CountRegionalDexNumberTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertGreaterThan(0, $this->getRegionalDexNumberCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals(4419, $this->getRegionalDexNumberCount());

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString('4419 regional dex numbers updated', $commandTester->getDisplay());
    }

    protected function getCommandName(): string
    {
        return 'app:update:regional_dex_numbers';
    }
}
