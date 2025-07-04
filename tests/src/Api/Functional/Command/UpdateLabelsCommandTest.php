<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\AbstractActionStarter;
use App\Api\ActionStarter\UpdateLabelsActionStarter;
use App\Api\Command\AbstractUpdateCommand;
use App\Api\Command\UpdateLabelsCommand;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdateLabels;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CounterTableTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;

/**
 * @internal
 */
#[CoversClass(UpdateLabelsCommand::class)]
#[CoversClass(AbstractUpdateCommand::class)]
#[CoversClass(UpdateLabelsActionStarter::class)]
#[CoversClass(AbstractActionStarter::class)]
#[CoversClass(UpdateLabels::class)]
#[CoversClass(AbstractActionMessage::class)]
#[CoversTrait(ActionEnderTrait::class)]
class UpdateLabelsCommandTest extends AbstractTestCaseCommand
{
    use CounterTableTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertEquals(5, $this->getTableCount('catch_state'));
        $this->assertEquals(3, $this->getTableCount('category_form'));
        $this->assertEquals(3, $this->getTableCount('regional_form'));
        $this->assertEquals(4, $this->getTableCount('special_form'));
        $this->assertEquals(7, $this->getTableCount('variant_form'));
        $this->assertEquals(10, $this->getTableCount('region'));
        $this->assertEquals(19, $this->getTableCount('type'));

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals(9, $this->getTableCount('catch_state'));
        $this->assertEquals(4, $this->getTableCount('category_form'));
        $this->assertEquals(4, $this->getTableCount('regional_form'));
        $this->assertEquals(5, $this->getTableCount('special_form'));
        $this->assertEquals(8, $this->getTableCount('variant_form'));
        $this->assertEquals(10, $this->getTableCount('region'));
        $this->assertEquals(20, $this->getTableCount('type'));

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("6 catch's states updated", $commandTester->getDisplay());
        $this->assertStringContainsString('4 category forms updated', $commandTester->getDisplay());
        $this->assertStringContainsString('4 regional forms updated', $commandTester->getDisplay());
        $this->assertStringContainsString('5 special forms updated', $commandTester->getDisplay());
        $this->assertStringContainsString('8 variant forms updated', $commandTester->getDisplay());
        $this->assertStringContainsString('10 regions updated', $commandTester->getDisplay());
        $this->assertStringContainsString('18 types updated', $commandTester->getDisplay());
    }

    #[\Override]
    protected function getCommandName(): string
    {
        return 'app:update:labels';
    }
}
