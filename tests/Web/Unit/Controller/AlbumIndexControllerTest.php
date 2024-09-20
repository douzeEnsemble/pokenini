<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller;

use App\Web\Controller\AlbumIndexController;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\Api\GetPokedexService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 */
#[CoversClass(AlbumIndexController::class)]
class AlbumIndexControllerTest extends TestCase
{
    public function testIndexApiException(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('1234567890')
        ;

        $validator = $this->createMock(ValidatorInterface::class);

        $getPokedexService = $this->createMock(GetPokedexService::class);
        $getPokedexService
            ->expects($this->once())
            ->method('get')
            ->willThrowException(
                new TransportException('Whoops!')
            )
            ->with(
                'douze',
                '121212',
            )
        ;

        $getLabelsService = $this->createMock(GetLabelsService::class);

        $controller = new AlbumIndexController(
            $userTokenService,
            $validator,
            $getPokedexService,
            $getLabelsService,
        );

        $request = $this->createMock(Request::class);
        $request->query = new InputBag(['t' => '121212']);

        $this->expectException(NotFoundHttpException::class);

        $controller->index($request, 'douze');
    }
}
