<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\GoogleAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @internal
 */
#[CoversClass(GoogleAuthenticator::class)]
class GoogleAuthenticatorTest extends TestCase
{
    public function testSupports(): void
    {
        $clientRegistry = $this->createMock(ClientRegistry::class);

        $router = $this->createMock(RouterInterface::class);

        $authenticator = new GoogleAuthenticator(
            $clientRegistry,
            $router,
            'listAdmin',
            'listTrainer',
            'listCollector',
        );

        $this->assertTrue(
            $authenticator->supports(
                new Request([], [], ['_route' => 'app_web_connect_googlecheck'])
            )
        );
        $this->assertFalse(
            $authenticator->supports(
                new Request([], [], ['_route' => 'app_web_connect_check'])
            )
        );
    }

    public function testStart(): void
    {
        $clientRegistry = $this->createMock(ClientRegistry::class);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('generate')
            ->with('app_web_home_index')
            ->willReturn('/home')
        ;

        $authenticator = new GoogleAuthenticator(
            $clientRegistry,
            $router,
            'listAdmin',
            'listTrainer',
            'listCollector',
        );

        $request = new Request();

        /** @var RedirectResponse $response */
        $response = $authenticator->start($request);

        $this->assertEquals('/home', $response->getTargetUrl());
    }
}
