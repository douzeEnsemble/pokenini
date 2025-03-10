<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\DiscordAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(DiscordAuthenticator::class)]
class DiscordAuthenticatorTest extends AbstractAuthenticatorTesting
{
    protected function getAuthenticatorClassName(): string
    {
        return DiscordAuthenticator::class;
    }

    protected function getAuthenticatorProviderCode(): string
    {
        return 'discord';
    }

    protected function getAuthenticatorProviderName(): string
    {
        return 'Discord';
    }
}
