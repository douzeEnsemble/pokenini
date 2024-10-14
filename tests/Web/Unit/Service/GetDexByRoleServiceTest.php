<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service;

use App\Web\Security\User;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetDexService;
use App\Web\Service\GetDexByRoleService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @internal
 */
#[CoversClass(GetDexByRoleService::class)]
class GetDexByRoleServiceTest extends TestCase
{
    public function testGetUserDexAsTrainer(): void
    {
        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->once())
            ->method('getWithPremium')
            ->with(
                '1234567890'
            )
            ->willReturn([
                ['un'],
                ['dos'],
                ['tres'],
            ])
        ;
        $getDexService
            ->expects($this->never())
            ->method('get')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleased')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleasedAndPremium')
        ;

        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $user = new User('1234567890');
        $user->addTrainerRole();

        $security = $this->createMock(Security::class);
        $security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $service = new GetDexByRoleService(
            $getDexService,
            $userTokenService,
            $security
        );

        $dex = $service->getUserDex();

        $this->assertSame(
            [
                ['un'],
                ['dos'],
                ['tres'],
            ],
            $dex
        );
    }

    public function testGetUserDexAsCollector(): void
    {
        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->once())
            ->method('getWithPremium')
            ->with(
                '1234567890'
            )
            ->willReturn([
                ['un'],
                ['dos'],
                ['tres'],
            ])
        ;
        $getDexService
            ->expects($this->never())
            ->method('get')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleased')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleasedAndPremium')
        ;

        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $user = new User('1234567890');
        $user->addTrainerRole();
        $user->addCollectorRole();

        $security = $this->createMock(Security::class);
        $security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $service = new GetDexByRoleService(
            $getDexService,
            $userTokenService,
            $security
        );

        $dex = $service->getUserDex();

        $this->assertSame(
            [
                ['un'],
                ['dos'],
                ['tres'],
            ],
            $dex
        );
    }

    public function testGetUserDexAsAdmin(): void
    {
        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->once())
            ->method('getWithUnreleasedAndPremium')
            ->with(
                '1234567890'
            )
            ->willReturn([
                ['un'],
                ['dos'],
                ['tres'],
            ])
        ;
        $getDexService
            ->expects($this->never())
            ->method('get')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithPremium')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleased')
        ;

        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $user = new User('1234567890');
        $user->addTrainerRole();
        $user->addAdminRole();

        $security = $this->createMock(Security::class);
        $security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user)
        ;

        $service = new GetDexByRoleService(
            $getDexService,
            $userTokenService,
            $security
        );

        $dex = $service->getUserDex();

        $this->assertSame(
            [
                ['un'],
                ['dos'],
                ['tres'],
            ],
            $dex
        );
    }

    public function testGetUserDexAsNull(): void
    {
        $getDexService = $this->createMock(GetDexService::class);
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleasedAndPremium')
        ;
        $getDexService
            ->expects($this->never())
            ->method('get')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithPremium')
        ;
        $getDexService
            ->expects($this->never())
            ->method('getWithUnreleased')
        ;

        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->never())
            ->method('getLoggedUserToken')
        ;

        $security = $this->createMock(Security::class);
        $security
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null)
        ;

        $service = new GetDexByRoleService(
            $getDexService,
            $userTokenService,
            $security
        );

        $dex = $service->getUserDex();

        $this->assertEmpty($dex);
    }
}
