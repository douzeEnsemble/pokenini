<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\PassageAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(PassageAuthenticator::class)]
class PassageAuthenticatorTest extends AbstractAuthenticatorTesting
{
    protected function getAuthenticatorClassName(): string
    {
        return PassageAuthenticator::class;
    }

    protected function getAuthenticatorProviderCode(): string
    {
        return 'passage';
    }

    protected function getAuthenticatorProviderName(): string
    {
        return 'Passage';
    }
}
