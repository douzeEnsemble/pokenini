<?php

declare(strict_types=1);

namespace App\Web\Controller\Connect;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/f')]
class FakeController extends AbstractController
{
    // @codeCoverageIgnoreStart
    #[Route(
        '/c',
        methods: ['GET'],
        condition: "'dev' === env('APP_ENV')"
    )]
    public function check(): void
    {
        // noting, all done by the authenticator
    }
    // @codeCoverageIgnoreEnd
}
