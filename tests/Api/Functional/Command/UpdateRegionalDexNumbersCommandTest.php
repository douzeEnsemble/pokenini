<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountRegionalDexNumberTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(UpdateRegionalDexNumbersCommandTest::class)]
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
