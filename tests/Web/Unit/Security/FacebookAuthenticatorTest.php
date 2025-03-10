<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\FacebookAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(FacebookAuthenticator::class)]
class FacebookAuthenticatorTest extends AbstractAuthenticatorTesting
{
    protected function getAuthenticatorClassName(): string
    {
        return FacebookAuthenticator::class;
    }

    protected function getAuthenticatorProviderCode(): string
    {
        return 'facebook';
    }

    protected function getAuthenticatorProviderName(): string
    {
        return 'Facebook';
    }
}
