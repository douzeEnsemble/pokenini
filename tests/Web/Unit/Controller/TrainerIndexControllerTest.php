<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller;

use App\Web\Controller\TrainerIndexController;
use App\Web\DTO\DexFilters;
use App\Web\Security\User;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetDexService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

/**
 * @internal
 */
#[CoversClass(TrainerIndexController::class)]
class TrainerIndexControllerTest extends TestCase
{
    public function testIndexTrainer(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $validator = $this->createMock(ValidatorInterface::class);

        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->once())
            ->method('get')
            ->with(
                '1234567890'
            )
            ->willReturn(['douze'])
        ;

        $user = new User('1234567890');
        $user->addTrainerRole();

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token)
        ;

        $twig = $this->createMock(Environment::class);
        $twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'Trainer/index.html.twig',
                [
                    'trainerDex' => ['douze'],
                    'filters' => DexFilters::createFromArray([]),
                ]
            )
        ;

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturn(true)
        ;
        $container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $tokenStorage,
                $twig
            )
        ;

        $controller = new TrainerIndexController(
            $userTokenService,
            $validator,
            $getDexService,
        );
        $controller->setContainer($container);

        $request = new Request();

        $response = $controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    public function testIndexAdmin(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $validator = $this->createMock(ValidatorInterface::class);

        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->once())
            ->method('getWithUnreleased')
            ->with(
                '1234567890'
            )
            ->willReturn(['treize'])
        ;

        $user = new User('1234567890');
        $user->addAdminRole();

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token)
        ;

        $twig = $this->createMock(Environment::class);
        $twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'Trainer/index.html.twig',
                [
                    'trainerDex' => ['treize'],
                    'filters' => DexFilters::createFromArray([]),
                ]
            )
        ;

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturn(true)
        ;
        $container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $tokenStorage,
                $twig
            )
        ;

        $controller = new TrainerIndexController(
            $userTokenService,
            $validator,
            $getDexService,
        );
        $controller->setContainer($container);

        $request = new Request();

        $response = $controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    /**
     * "NoRole is a unit case ony.
     * Symfony Firewall make sure onlye ROLE_TRAINER access to this controller.
     */
    public function testIndexNoRole(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $validator = $this->createMock(ValidatorInterface::class);

        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->once())
            ->method('get')
            ->with(
                '1234567890'
            )
            ->willReturn([])
        ;

        $user = new User('1234567890');

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token)
        ;

        $twig = $this->createMock(Environment::class);
        $twig
            ->expects($this->once())
            ->method('render')
            ->with(
                'Trainer/index.html.twig',
                [
                    'trainerDex' => [],
                    'filters' => DexFilters::createFromArray([]),
                ]
            )
        ;

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturn(true)
        ;
        $container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $tokenStorage,
                $twig
            )
        ;

        $controller = new TrainerIndexController(
            $userTokenService,
            $validator,
            $getDexService,
        );
        $controller->setContainer($container);

        $request = new Request();

        $response = $controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }

    public function testIndexUnauthorized(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);

        $validator = $this->createMock(ValidatorInterface::class);

        $getDexService = $this->createMock(GetDexService::class);

        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null)
        ;

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token)
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
            ->willReturn($tokenStorage)
        ;

        $controller = new TrainerIndexController(
            $userTokenService,
            $validator,
            $getDexService,
        );
        $controller->setContainer($container);

        $request = new Request();

        $response = $controller->index($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }
}
