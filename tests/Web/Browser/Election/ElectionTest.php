<?php

declare(strict_types=1);

namespace App\Tests\Web\Browser\Trainer;

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
class ElectionTest extends AbstractBrowserTestCase
{
    use TestNavTrait;

    public function testChecked(): void
    {
        $client = $this->getNewClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $client->request('GET', '/fr/election/demolite');

        $this->assertSelectorTextContains('#election-counter', '0');

        $client->executeScript("
            var checkbox = document.querySelector('#vote-bulbasaur');
            checkbox.checked = true;
            var event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        ");

        $this->assertSelectorTextContains('#election-counter', '1');

        $client->executeScript("
            var checkbox = document.querySelector('#vote-ivysaur');
            checkbox.checked = true;
            var event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        ");

        $this->assertSelectorTextContains('#election-counter', '2');

        $client->executeScript("
            var checkbox = document.querySelector('#vote-ivysaur');
            checkbox.checked = false;
            var event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        ");

        $this->assertSelectorTextContains('#election-counter', '1');
    }
}
