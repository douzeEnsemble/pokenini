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
class UpdateGamesAndDexCommandTest extends AbstractTestCaseCommand
{
    use CounterTableTrait;
    use CountActionLogTrait;

    public function testUpdate(): void
    {
        $this->assertEquals(9, $this->getTableCount('game_generation'));
        $this->assertEquals(19, $this->getTableCount('game_bundle'));
        $this->assertEquals(39, $this->getTableCount('game'));
        $this->assertEquals(8, $this->getTableCount('dex'));

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();

        $commandTester->assertCommandIsSuccessful();

        $this->assertEquals(9, $this->getTableCount('game_generation'));
        $this->assertEquals(19, $this->getTableCount('game_bundle'));
        $this->assertEquals(39, $this->getTableCount('game'));
        $this->assertEquals(24, $this->getTableCount('dex'));

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertStringContainsString("9 game's generations updated", $commandTester->getDisplay());
        $this->assertStringContainsString("18 game's bundles updated", $commandTester->getDisplay());
        $this->assertStringContainsString('36 games updated', $commandTester->getDisplay());
        $this->assertStringContainsString('21 dex updated', $commandTester->getDisplay());
    }

    protected function getCommandName(): string
    {
        return 'app:update:games_and_dex';
    }
}
