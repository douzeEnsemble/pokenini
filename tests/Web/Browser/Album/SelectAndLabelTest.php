<?php

declare(strict_types=1);

namespace App\Tests\Web\Browser\Album;

use App\Web\Security\User;
use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Tests\Web\Browser\AbstractBrowserTestCase;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Panther\DomCrawler\Field\ChoiceFormField;

#[Group('browser-testing')]
class SelectAndLabelTest extends AbstractBrowserTestCase
{
    use TestNavTrait;

    public function testActionCatchStateGoldSilverCrystal(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/album/goldsilvercrystal');

        $this->assertCountFilter($crawler, 3, '.album-case');
        $this->assertCountFilter($crawler, 3, '.album-case-action');
        $this->assertCountFilter($crawler, 3, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 3, '.album-case-catch-state');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 3, '.album-case-catch-state a.album-case-catch-state-label');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state span.album-case-catch-state-label');
        $this->assertCountFilter(
            $crawler,
            3,
            '.album-case-catch-state .album-case-catch-state-edit-action'
        );

        $this->assertEquals(
            '#chikorita',
            $crawler
                ->filter('#chikorita .album-case-catch-state-edit-action')
                ->attr('href')
        );
    }

    public function testActionCatchStateDemo(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/album/demo');

        $this->assertCountFilter($crawler, 25, '.album-case');
        $this->assertCountFilter($crawler, 25, '.album-case-action');
        $this->assertCountFilter($crawler, 25, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 25, '.album-case-catch-state');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 25, '.album-case-catch-state a.album-case-catch-state-label');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state span.album-case-catch-state-label');
        $this->assertCountFilter(
            $crawler,
            25,
            '.album-case-catch-state .album-case-catch-state-edit-action'
        );

        $this->assertEquals(
            '#bulbasaur',
            $crawler
                ->filter('#bulbasaur .album-case-catch-state-edit-action')
                ->attr('href')
        );
    }

    public function testActionCatchStateDemoList3(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/album/demolist3');

        $this->assertCountFilter($crawler, 41, '.album-case');
        $this->assertCountFilter($crawler, 41, '.album-case-action');
        $this->assertCountFilter($crawler, 41, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 41, '.album-case-catch-state');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 41, '.album-case-catch-state a.album-case-catch-state-label');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state span.album-case-catch-state-label');
        $this->assertCountFilter(
            $crawler,
            41,
            '.album-case-catch-state .album-case-catch-state-edit-action'
        );

        $this->assertEquals(
            '#bulbasaur',
            $crawler
                ->filter('#bulbasaur .album-case-catch-state-edit-action')
                ->attr('href')
        );
    }

    public function testActionCatchStateToggle(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $client->request('GET', '/fr/album/demo');

        $this->assertSelectorIsVisible('#bulbasaur .album-case-catch-state');
        $this->assertSelectorIsNotVisible('#bulbasaur .album-case-action');

        $client->click(
            $client
            ->getCrawler()
            ->filter('#bulbasaur-catch-state-edit-action')
            ->link()
        );

        $this->assertSelectorIsNotVisible('#bulbasaur .album-case-catch-state');
        $this->assertSelectorIsVisible('#bulbasaur .album-case-action');
    }

    public function testActionCatchStateToggleWithLabel(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $client->request('GET', '/fr/album/demo');

        $this->assertSelectorIsVisible('#bulbasaur .album-case-catch-state');
        $this->assertSelectorIsNotVisible('#bulbasaur .album-case-action');

        $client->click(
            $client
            ->getCrawler()
            ->filter('#bulbasaur .album-case-catch-state .album-case-catch-state-label')
            ->link()
        );

        $this->assertSelectorIsNotVisible('#bulbasaur .album-case-catch-state');
        $this->assertSelectorIsVisible('#bulbasaur .album-case-action');
    }

    public function testActionCatchStateToggleAllEdit(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/album/demo');

        $this->assertCountFilter($crawler, 25, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-read-action[hidden]');

        $client->click(
            $client
            ->getCrawler()
            ->filter('.album-all-catch-state-edit-action')
            ->link()
        );

        $this->assertCountFilter($crawler, 0, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 25, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-read-action[hidden]');
    }

    public function testActionCatchStateToggleAllEditThenRead(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/album/demo');

        $this->assertCountFilter($crawler, 25, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-read-action[hidden]');

        $client->click(
            $client
            ->getCrawler()
            ->filter('.album-all-catch-state-edit-action')
            ->link()
        );

        $this->assertCountFilter($crawler, 0, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 25, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-read-action[hidden]');

        $client->click(
            $client
            ->getCrawler()
            ->filter('.album-all-catch-state-read-action')
            ->link()
        );

        $this->assertCountFilter($crawler, 25, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-read-action[hidden]');
    }

    public function testActionCatchStateToggleAllEditWithAlreadyInEditMode(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/album/demo');

        $this->assertCountFilter($crawler, 25, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-read-action[hidden]');

        $client->click(
            $client
            ->getCrawler()
            ->filter('#bulbasaur-catch-state-edit-action')
            ->link()
        );

        $this->assertCountFilter($crawler, 24, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-read-action[hidden]');

        $client->click(
            $client
            ->getCrawler()
            ->filter('.album-all-catch-state-edit-action')
            ->link()
        );

        $this->assertCountFilter($crawler, 0, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 25, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-read-action[hidden]');

        $client->click(
            $client
            ->getCrawler()
            ->filter('.album-all-catch-state-read-action')
            ->link()
        );

        $this->assertCountFilter($crawler, 25, '.album-case-action[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-case-catch-state[hidden]');
        $this->assertCountFilter($crawler, 0, '.album-all-catch-state-edit-action[hidden]');
        $this->assertCountFilter($crawler, 1, '.album-all-catch-state-read-action[hidden]');
    }

    public function testActionCatchStateChangeSuccess(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $client->request('GET', '/fr/album/demo');

        $this->assertSelectorIsNotVisible('#successToast-bulbasaur');
        $this->assertSelectorIsNotVisible('#errorToast-bulbasaur');
        $this->assertSelectorAttributeContains('#bulbasaur', 'class', 'catch-state-no');
        $this->assertSelectorAttributeNotContains('#bulbasaur', 'class', 'catch-state-totrade');

        $client->executeScript("document.getElementById('bulbasaur').scrollIntoView();");

        $client->click(
            $client
            ->getCrawler()
            ->filter('#bulbasaur-catch-state-edit-action')
            ->link()
        );

        $form = $client->getCrawler()->filter('#album-form')->form();
        /** @var ChoiceFormField $field */
        $field = $form->get('catch-state[bulbasaur]');
        $field->setValue('totrade');

        $this->assertSelectorWillBeVisible('#successToast-bulbasaur');
        $this->assertSelectorWillNotBeVisible('#successToast-bulbasaur');
        $this->assertSelectorWillNotBeVisible('#errorToast-bulbasaur');

        $this->assertSelectorAttributeNotContains('#bulbasaur', 'class', 'catch-state-no');
        $this->assertSelectorAttributeContains('#bulbasaur', 'class', 'catch-state-totrade');
    }

    public function testActionCatchStateChangeError(): void
    {
        $client = $this->getNewClient();

        $user = new User('12');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $client->request('GET', '/fr/album/demo');

        $this->assertSelectorIsNotVisible('#errorToast-squirtle');
        $this->assertSelectorIsNotVisible('#successToast-squirtle');
        $this->assertSelectorAttributeContains('#squirtle', 'class', 'catch-state-no');
        $this->assertSelectorAttributeNotContains('#squirtle', 'class', 'catch-state-tobreed');

        $client->executeScript("document.getElementById('squirtle').scrollIntoView();");

        $client->click(
            $client
            ->getCrawler()
            ->filter('#squirtle-catch-state-edit-action')
            ->link()
        );

        $form = $client->getCrawler()->filter('#album-form')->form();
        /** @var ChoiceFormField $field */
        $field = $form->get('catch-state[squirtle]');
        $field->setValue('tobreed');

        $this->assertSelectorWillBeVisible('#errorToast-squirtle');
        $this->assertSelectorWillNotBeVisible('#errorToast-squirtle');
        $this->assertSelectorWillNotBeVisible('#successToast-squirtle');

        $this->assertSelectorAttributeNotContains('#squirtle', 'class', 'catch-state-no');
        $this->assertSelectorAttributeContains('#squirtle', 'class', 'catch-state-tobreed');
    }
}
