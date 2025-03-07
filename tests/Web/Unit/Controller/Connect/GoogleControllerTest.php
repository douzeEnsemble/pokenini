<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller\Connect;

use App\Web\Controller\Connect\GoogleController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[CoversClass(GoogleController::class)]
class GoogleControllerTest extends TestCase
{
    public function testGoto(): void
    {
        $controller = new GoogleController();

        $client = $this->createMock(OAuth2ClientInterface::class);
        $client
            ->expects($this->once())
            ->method('redirect')
            ->with(['openid'], [])
            ->willReturn(new Response())
        ;

        $clientRegistry = $this->createMock(ClientRegistry::class);
        $clientRegistry
            ->expects($this->once())
            ->method('getClient')
            ->with('google')
            ->willReturn($client)
        ;

        $controller->goto($clientRegistry);
    }
}
