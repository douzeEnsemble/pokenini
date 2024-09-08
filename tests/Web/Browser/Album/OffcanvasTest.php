<?php

declare(strict_types=1);

namespace App\Tests\Web\Browser\Album;

use App\Tests\Web\Browser\AbstractBrowserTestCase;
use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Web\Security\User;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Group;

/**
 * @internal
 */
#[CoversNothing]
#[Group('browser-testing')]
class OffcanvasTest extends AbstractBrowserTestCase
{
    use TestNavTrait;

    public function testOffcanvas(): void
    {
        $client = $this->getNewClient();

        $user = new User('109903422692691643666');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $client->request('GET', '/fr/album/demolite');

        $this->assertSelectorIsNotVisible('#offcanvas');

        $client->executeScript('document.querySelector(\'.open-offcanvas\').click()');

        $this->assertSelectorWillBeVisible('#offcanvas');

        $client->executeScript('document.querySelector(\'#offcanvas .btn-close\').click()');

        $this->assertSelectorWillNotBeVisible('#offcanvas');
    }
}
