<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service;

use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ElectionTopApiService;
use App\Web\Service\ElectionTopService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ElectionTopService::class)]
class ElectionTopServiceTest extends TestCase
{
    public function testGetTop(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('8800088')
        ;

        $apiService = $this->createMock(ElectionTopApiService::class);
        $apiService
            ->expects($this->once())
            ->method('getTop')
            ->with(
                '8800088',
                'whatever',
                12,
            )
        ;

        $service = new ElectionTopService($userTokenService, $apiService, 12);
        $service->getTop('demo', 'whatever');
    }
}
