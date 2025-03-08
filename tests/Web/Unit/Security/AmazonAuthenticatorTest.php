<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\AmazonAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @internal
 */
#[CoversClass(AmazonAuthenticator::class)]
class AmazonAuthenticatorTest extends TestCase
{
    public function testSupports(): void
    {
        $clientRegistry = $this->createMock(ClientRegistry::class);

        $router = $this->createMock(RouterInterface::class);

        $authenticator = new AmazonAuthenticator(
            $clientRegistry,
            $router,
            'listAdmin',
            'listTrainer',
            'listCollector',
            true,
        );

        $this->assertTrue(
            $authenticator->supports(
                new Request([], [], ['_route' => 'app_web_connect_amazon_check'])
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

        $authenticator = new AmazonAuthenticator(
            $clientRegistry,
            $router,
            'listAdmin',
            'listTrainer',
            'listCollector',
            true,
        );

        $request = new Request();

        /** @var RedirectResponse $response */
        $response = $authenticator->start($request);

        $this->assertEquals('/home', $response->getTargetUrl());
    }
}
