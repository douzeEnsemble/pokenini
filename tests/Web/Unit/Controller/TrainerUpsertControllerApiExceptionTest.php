<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller;

use App\Web\Controller\TrainerUpsertController;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ModifyDexService;
use App\Web\Service\CacheInvalidator\AlbumCacheInvalidatorService;
use App\Web\Service\CacheInvalidator\DexCacheInvalidatorService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * @internal
 */
#[CoversClass(TrainerUpsertController::class)]
class TrainerUpsertControllerApiExceptionTest extends TestCase
{
    public function testUpsertApiTransportException(): void
    {
        $this->assertApiException(
            new TransportException('Whoops!', 500),
            'Whoops!'
        );
    }

    public function testUpsertApiHttpException(): void
    {
        $this->assertApiException(
            $this->createMock(ServerExceptionInterface::class),
            '',
        );
    }

    private function assertApiException(\Throwable $exception, string $expectedMessage): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $validator = $this->createMock(ValidatorInterface::class);

        $modifyDexService = $this->createMock(ModifyDexService::class);
        $modifyDexService
            ->expects($this->once())
            ->method('modify')
            ->willThrowException(
                $exception
            )
            ->with(
                'douze',
                '{}',
                '1234567890'
            )
        ;

        $albumCacheInvalidatorService = $this->createMock(AlbumCacheInvalidatorService::class);

        $dexCacheInvalidatorService = $this->createMock(DexCacheInvalidatorService::class);

        $authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $authorizationChecker
            ->expects($this->once())
            ->method('isGranted')
            ->willReturn(true)
        ;

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('has')
            ->willReturn(true)
        ;
        $container
            ->expects($this->once())
            ->method('get')
            ->willReturn($authorizationChecker)
        ;

        $controller = new TrainerUpsertController(
            $userTokenService,
            $validator,
            $modifyDexService,
            $albumCacheInvalidatorService,
            $dexCacheInvalidatorService,
        );
        $controller->setContainer($container);

        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{}')
        ;

        $response = $controller->upsert('douze', $request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('{"error":"'.$expectedMessage.'"}', $response->getContent());
    }
}
