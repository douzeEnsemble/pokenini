<?php

namespace App\Tests\Api\Unit\Command;

use App\Api\ActionStarter\UpdateCollectionsAvailabilitiesActionStarter;
use App\Api\Command\UpdateCollectionsAvailabilitiesCommand;
use App\Api\Entity\ActionLog;
use App\Api\Repository\ActionLogsRepository;
use App\Api\Service\UpdaterService\CollectionsAvailabilitiesUpdaterService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
#[CoversClass(UpdateCollectionsAvailabilitiesCommand::class)]
class UpdateCollectionsAvailabilitiesCommandTest extends TestCase
{
    public function testFailureOnException(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);

        $actionLog = new ActionLog('UpdateCollectionsAvailabilities');

        $repository = $this->createMock(ActionLogsRepository::class);
        $repository
            ->expects($this->once())
            ->method('find')
            ->with('')
            ->willReturn($actionLog)
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($repository)
        ;
        $entityManager
            ->expects($this->once())
            ->method('persist')
        ;
        $entityManager
            ->expects($this->exactly(2))
            ->method('flush')
        ;

        $actionStarter = new UpdateCollectionsAvailabilitiesActionStarter($entityManager);

        $updaterService = $this->createMock(CollectionsAvailabilitiesUpdaterService::class);
        $updaterService
            ->expects($this->once())
            ->method('execute')
            ->willThrowException(new \Exception('Oh zut'))
        ;

        $command = new UpdateCollectionsAvailabilitiesCommand(
            $translator,
            $entityManager,
            $actionStarter,
            $updaterService,
        );

        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('writeln')
            ->with('<error>Oh zut</error>')
        ;

        $command->run($input, $output);

        $this->assertEquals($actionLog->errorTrace, 'Oh zut');
        $this->assertNotNull($actionLog->doneAt);
    }
}
