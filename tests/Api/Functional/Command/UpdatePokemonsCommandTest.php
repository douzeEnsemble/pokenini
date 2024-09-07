<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CounterTableTrait;

/**
 * @internal
 *
 * @coversNothing
 */
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
