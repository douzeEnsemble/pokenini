<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller;

use App\Web\Controller\ElectionController;
use App\Web\Service\ElectionVoteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @internal
 */
#[CoversClass(ElectionController::class)]
class ElectionControllerTest extends TestCase
{
    public function testVote(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"winners_slugs": ["pichu"], "losers_slugs": ["pikachu"]}')
        ;

        $electionVoteService = $this->createMock(ElectionVoteService::class);
        $electionVoteService
            ->expects($this->once())
            ->method('vote')
        ;

        $controller = new ElectionController();

        $router = $this->createMock(Router::class);
        $router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('/fr/election/demo')
        ;

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->exactly(1))
            ->method('get')
            ->willReturn($router)
        ;
        $controller->setContainer($container);

        /** @var RedirectResponse $response */
        $response = $controller->vote(
            $request,
            $electionVoteService,
            'demo',
            ''
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/fr/election/demo', $response->getTargetUrl());
    }

    public function testVoteEmpty(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getContent')
            ->willReturn(null)
        ;

        $electionVoteService = $this->createMock(ElectionVoteService::class);

        $controller = new ElectionController();

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Content cannot be empty');
        $controller->vote(
            $request,
            $electionVoteService,
            'demo',
            ''
        );
    }

    public function testVoteNonArray(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('12')
        ;

        $electionVoteService = $this->createMock(ElectionVoteService::class);

        $controller = new ElectionController();

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Content must be a JSON array');
        $controller->vote(
            $request,
            $electionVoteService,
            'demo',
            ''
        );
    }

    public function testVoteNonvalid(): void
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"winners_slugs": []}')
        ;

        $electionVoteService = $this->createMock(ElectionVoteService::class);

        $controller = new ElectionController();

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('The required option "losers_slugs');
        $controller->vote(
            $request,
            $electionVoteService,
            'demo',
            ''
        );
    }
}
