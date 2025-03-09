<?php

declare(strict_types=1);

namespace App\Web\Controller\Connect;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/g')]
class GoogleController extends AbstractConnectController
{
    protected function getProviderName(): string
    {
        return 'google';
    }

    protected function getScope(): string
    {
        return 'openid';
    }
}
