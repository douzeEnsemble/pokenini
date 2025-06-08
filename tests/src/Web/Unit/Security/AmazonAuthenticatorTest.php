<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\AmazonAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(AmazonAuthenticator::class)]
class AmazonAuthenticatorTest extends AbstractAuthenticatorTesting
{
    #[\Override]
    protected function getAuthenticatorClassName(): string
    {
        return AmazonAuthenticator::class;
    }

    #[\Override]
    protected function getAuthenticatorProviderCode(): string
    {
        return 'amazon';
    }

    #[\Override]
    protected function getAuthenticatorProviderName(): string
    {
        return 'Amazon';
    }
}
