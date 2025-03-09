<?php

declare(strict_types=1);

namespace App\Web\Controller\Connect;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/fb')]
class FacebookController extends AbstractConnectController
{
    protected function getProviderName(): string
    {
        return 'facebook';
    }

    protected function getScope(): string
    {
        return 'openid';
    }
}
