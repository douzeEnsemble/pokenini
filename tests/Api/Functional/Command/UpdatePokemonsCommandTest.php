<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\AbstractActionStarter;
use App\Api\ActionStarter\UpdatePokemonsActionStarter;
use App\Api\Command\AbstractUpdateCommand;
use App\Api\Command\UpdatePokemonsCommand;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\UpdatePokemons;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CounterTableTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdatePokemonsCommand::class)]
#[CoversClass(AbstractUpdateCommand::class)]
#[CoversClass(UpdatePokemonsActionStarter::class)]
#[CoversClass(AbstractActionStarter::class)]
#[CoversClass(UpdatePokemons::class)]
#[CoversClass(AbstractActionMessage::class)]
#[CoversClass(ActionEnderTrait::class)]
class UpdatePokemonsCommandTest extends AbstractTestCaseCommand
{
    use CounterTableTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertEquals(26, $this->getTableCount('pokemon'));

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();
        $this->assertEquals(1818, $this->getTableCount('pokemon'));

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString('1817 pokémons updated', $commandTester->getDisplay());
    }

    protected function getCommandName(): string
    {
        return 'app:update:pokemons';
    }
}
