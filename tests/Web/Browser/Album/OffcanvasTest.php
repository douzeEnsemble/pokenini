<?php

declare(strict_types=1);

namespace App\Tests\Web\Browser\Album;

use App\Web\Security\User;
use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Tests\Web\Browser\AbstractBrowserTestCase;

/**
 * @group browser-testing
 */
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
