<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller\Connect;

use App\Web\Controller\Connect\AmazonController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[CoversClass(AmazonController::class)]
class AmazonControllerTest extends TestCase
{
    public function testGoto(): void
    {
        $controller = new AmazonController();

        $client = $this->createMock(OAuth2ClientInterface::class);
        $client
            ->expects($this->once())
            ->method('redirect')
            ->with(['profile'], [])
            ->willReturn(new Response())
        ;

        $clientRegistry = $this->createMock(ClientRegistry::class);
        $clientRegistry
            ->expects($this->once())
            ->method('getClient')
            ->with('amazon')
            ->willReturn($client)
        ;

        $controller->goto($clientRegistry);
    }
}
