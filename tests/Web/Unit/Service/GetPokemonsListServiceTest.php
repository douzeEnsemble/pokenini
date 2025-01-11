<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service;

use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetPokemonsService;
use App\Web\Service\GetPokemonsListService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GetPokemonsListService::class)]
class GetPokemonsListServiceTest extends TestCase
{
    public function testGet(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('8800088')
        ;

        $getPokemonsService = $this->createMock(GetPokemonsService::class);
        $getPokemonsService
            ->expects($this->once())
            ->method('get')
            ->with(
                '8800088',
                'douze',
                '',
                12,
            )
            ->willReturn([
                [
                    'poke' => '1',
                ],
                [
                    'poke' => '2',
                ],
            ])
        ;

        $service = new GetPokemonsListService($userTokenService, $getPokemonsService);
        $list = $service->get('douze', '', 12);

        $this->assertSame(
            [
                [
                    'poke' => '1',
                ],
                [
                    'poke' => '2',
                ],
            ],
            $list,
        );
    }
}
