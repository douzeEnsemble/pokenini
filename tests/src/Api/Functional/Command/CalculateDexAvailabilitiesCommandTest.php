<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Command;

use App\Api\ActionEnder\ActionEnderTrait;
use App\Api\ActionStarter\AbstractActionStarter;
use App\Api\ActionStarter\CalculateDexAvailabilitiesActionStarter;
use App\Api\Command\AbstractCalculateCommand;
use App\Api\Command\CalculateDexAvailabilitiesCommand;
use App\Api\Message\AbstractActionMessage;
use App\Api\Message\CalculateDexAvailabilities;
use App\Api\Repository\PokemonsRepository;
use App\Api\Service\CalculatorService\DexAvailabilitiesCalculatorService;
use App\Tests\Api\Common\Traits\CounterTrait\CountActionLogTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountDexAvailabilityTrait;
use App\Tests\Api\Common\Traits\CounterTrait\CountPokemonTrait;
use App\Tests\Api\Common\Traits\HasserTrait\HasDexAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;

/**
 * @internal
 */
#[CoversClass(CalculateDexAvailabilitiesCommand::class)]
#[CoversClass(DexAvailabilitiesCalculatorService::class)]
#[CoversClass(AbstractCalculateCommand::class)]
#[CoversClass(CalculateDexAvailabilitiesActionStarter::class)]
#[CoversClass(AbstractActionStarter::class)]
#[CoversClass(CalculateDexAvailabilities::class)]
#[CoversClass(AbstractActionMessage::class)]
#[CoversTrait(ActionEnderTrait::class)]
class CalculateDexAvailabilitiesCommandTest extends AbstractTestCaseCommand
{
    use CountPokemonTrait;
    use CountDexAvailabilityTrait;
    use HasDexAvailabilityTrait;
    use CountActionLogTrait;

    public function testNoDexAvailabilities(): void
    {
        /** @var PokemonsRepository $repo */
        $repo = static::getContainer()->get(PokemonsRepository::class);
        $repo->removeAll();

        $this->assertEquals(0, $this->getPokemonNotDeletedCount());

        $this->assertEquals(70, $this->getDexAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString("0 dex' availabilities calculated", $commandTester->getDisplay());

        $this->assertEquals(0, $this->getDexAvailabilityCount());

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());
    }

    public function testDexAvailabilities(): void
    {
        $this->assertEquals(70, $this->getDexAvailabilityCount());

        $initialToProcessCount = $this->getActionLogToProcessCount();
        $initialDoneCount = $this->getActionLogDoneCount();

        $commandTester = $this->executeCommand();
        $commandTester->assertCommandIsSuccessful();

        $this->assertStringContainsString("103 dex' availabilities calculated", $commandTester->getDisplay());

        $this->assertEquals(103, $this->getDexAvailabilityCount());

        $this->assertEquals($initialToProcessCount, $this->getActionLogToProcessCount());
        $this->assertEquals($initialDoneCount + 1, $this->getActionLogDoneCount());

        $this->assertTrue($this->hasDexAvailability('Red / Green / Blue / Yellow', 'bulbasaur'));
    }

    #[\Override]
    protected function getCommandName(): string
    {
        return 'app:calculate:dex_availabilities';
    }
}
