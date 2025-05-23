<?php

declare(strict_types=1);

namespace App\Web\Security;

class AmazonAuthenticator extends AbstractAuthenticator
{
    protected function getProviderCode(): string
    {
        return 'amazon';
    }

    protected function getProviderName(): string
    {
        return 'Amazon';
    }
}
