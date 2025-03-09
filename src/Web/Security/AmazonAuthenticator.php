<?php

declare(strict_types=1);

namespace App\Web\Security;

class AmazonAuthenticator extends AbstractAuthenticator
{
    public function getProviderCode(): string
    {
        return 'amazon';
    }

    public function getProviderName(): string
    {
        return 'Amazon';
    }
}
