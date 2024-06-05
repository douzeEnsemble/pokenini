<?php

declare(strict_types=1);

namespace App\Tests\Web\Browser\Trainer;

use App\Web\Security\User;
use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Tests\Web\Browser\AbstractBrowserTestCase;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Panther\DomCrawler\Field\ChoiceFormField;

#[Group('browser-testing')]
class CustomAlbumTrainerTest extends AbstractBrowserTestCase
{
    use TestNavTrait;

    public function testSuccessTick(): void
    {
        $client = $this->getNewClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/trainer');

        $this->assertSelectorIsNotVisible('#successToast-goldsilvercrystal');

        $client->executeScript("document.getElementById('trainer-dex-goldsilvercrystal').scrollIntoView();");

        $form = $crawler->filter('form[data-dex="goldsilvercrystal"]')->form();
        /** @var ChoiceFormField $field */
        $field = $form->get('goldsilvercrystal-is_on_home');
        $field->tick();

        $this->assertSelectorWillBeVisible('#successToast-goldsilvercrystal');
        $this->assertSelectorWillNotBeVisible('#successToast-goldsilvercrystal');
        $this->assertSelectorWillNotBeVisible('#errorToast-goldsilvercrystal');
    }

    public function testSuccessUntick(): void
    {
        $client = $this->getNewClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/trainer');

        $this->assertSelectorIsNotVisible('#successToast-goldsilvercrystal');

        $client->executeScript("document.getElementById('trainer-dex-goldsilvercrystal').scrollIntoView();");

        $form = $crawler->filter('form[data-dex="goldsilvercrystal"]')->form();
        /** @var ChoiceFormField $field */
        $field = $form->get('goldsilvercrystal-is_private');
        $field->untick();

        $this->assertSelectorWillBeVisible('#successToast-goldsilvercrystal');
        $this->assertSelectorWillNotBeVisible('#successToast-goldsilvercrystal');
        $this->assertSelectorWillNotBeVisible('#errorToast-goldsilvercrystal');
    }

    public function testErrorTick(): void
    {
        $client = $this->getNewClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/trainer');

        $this->assertSelectorIsNotVisible('#errorToast-redgreenblueyellow');

        $client->executeScript("document.getElementById('trainer-dex-redgreenblueyellow').scrollIntoView();");

        $form = $crawler->filter('form[data-dex="redgreenblueyellow"]')->form();
        /** @var ChoiceFormField $field */
        $field = $form->get('redgreenblueyellow-is_on_home');
        $field->tick();

        $this->assertSelectorWillBeVisible('#errorToast-redgreenblueyellow');
        $this->assertSelectorWillNotBeVisible('#errorToast-redgreenblueyellow');
        $this->assertSelectorWillNotBeVisible('#successToast-redgreenblueyellow');
    }

    public function testErrorUntick(): void
    {
        $client = $this->getNewClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $this->loginUser($client, $user);

        $crawler = $client->request('GET', '/fr/trainer');

        $this->assertSelectorIsNotVisible('#errorToast-redgreenblueyellow');

        $client->executeScript("document.getElementById('trainer-dex-redgreenblueyellow').scrollIntoView();");

        $form = $crawler->filter('form[data-dex="redgreenblueyellow"]')->form();
        /** @var ChoiceFormField $field */
        $field = $form->get('redgreenblueyellow-is_private');
        $field->untick();

        $this->assertSelectorWillBeVisible('#errorToast-redgreenblueyellow');
        $this->assertSelectorWillNotBeVisible('#errorToast-redgreenblueyellow');
        $this->assertSelectorWillNotBeVisible('#successToast-redgreenblueyellow');
    }
}
