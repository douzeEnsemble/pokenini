<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\GoogleAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GoogleAuthenticator::class)]
class GoogleAuthenticatorTest extends AbstractAuthenticatorTesting
{
    protected function getAuthenticatorClassName(): string
    {
        return GoogleAuthenticator::class;
    }

    protected function getAuthenticatorProviderCode(): string
    {
        return 'google';
    }
}
