<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Security;

use App\Web\Security\DiscordAuthenticator;
use App\Web\Security\User;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @internal
 */
#[CoversClass(DiscordAuthenticator::class)]
class DiscordAuthenticatorAuthenticateClosedTest extends TestCase
{
    public function testAuthenticateUser(): void
    {
        $authenticator = $this->getDiscordAuthenticator(
            '1313131313',
            '2121212121,1313131313',
            '2121212121',
        );

        $request = $this->createMock(Request::class);

        $validationPassport = $authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $validationPassport);

        /** @var User $user */
        $user = $validationPassport->getUser();
        $this->assertFalse($user->isAnAdmin());
        $this->assertFalse($user->isATrainer());
        $this->assertFalse($user->isACollector());
        $this->assertEquals('1212121212000000000000012', $user->getId());
        $this->assertEquals('1212121212000000000000012', $user->getUserIdentifier());
    }

    public function testAuthenticateTrainer(): void
    {
        $authenticator = $this->getDiscordAuthenticator(
            '1313131313',
            '2121212121,1313131313,1212121212000000000000012',
            '2121212121,1313131313',
        );

        $request = $this->createMock(Request::class);

        $validationPassport = $authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $validationPassport);

        /** @var User $user */
        $user = $validationPassport->getUser();
        $this->assertFalse($user->isAnAdmin());
        $this->assertTrue($user->isATrainer());
        $this->assertFalse($user->isACollector());
        $this->assertEquals('1212121212000000000000012', $user->getId());
        $this->assertEquals('1212121212000000000000012', $user->getUserIdentifier());
    }

    public function testAuthenticateCollector(): void
    {
        $authenticator = $this->getDiscordAuthenticator(
            '1313131313',
            '2121212121,1313131313',
            '2121212121,1212121212000000000000012',
        );

        $request = $this->createMock(Request::class);

        $validationPassport = $authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $validationPassport);

        /** @var User $user */
        $user = $validationPassport->getUser();
        $this->assertFalse($user->isAnAdmin());
        $this->assertFalse($user->isATrainer());
        $this->assertTrue($user->isACollector());
        $this->assertEquals('1212121212000000000000012', $user->getId());
        $this->assertEquals('1212121212000000000000012', $user->getUserIdentifier());
    }

    public function testAuthenticateAdmin(): void
    {
        $authenticator = $this->getDiscordAuthenticator(
            '1313131313,1212121212000000000000012',
            '2121212121,1313131313',
            '2121212121',
        );

        $request = $this->createMock(Request::class);

        $validationPassport = $authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $validationPassport);

        /** @var User $user */
        $user = $validationPassport->getUser();
        $this->assertTrue($user->isAnAdmin());
        $this->assertFalse($user->isATrainer());
        $this->assertFalse($user->isACollector());
        $this->assertEquals('1212121212000000000000012', $user->getId());
        $this->assertEquals('1212121212000000000000012', $user->getUserIdentifier());
    }

    public function testAuthenticateAdminTrainer(): void
    {
        $authenticator = $this->getDiscordAuthenticator(
            '1313131313,1212121212000000000000012',
            '2121212121,1313131313,1212121212000000000000012',
            '2121212121,',
        );

        $request = $this->createMock(Request::class);

        $validationPassport = $authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $validationPassport);

        /** @var User $user */
        $user = $validationPassport->getUser();
        $this->assertTrue($user->isAnAdmin());
        $this->assertTrue($user->isATrainer());
        $this->assertFalse($user->isACollector());
        $this->assertEquals('1212121212000000000000012', $user->getId());
        $this->assertEquals('1212121212000000000000012', $user->getUserIdentifier());
    }

    public function testAuthenticateAdminTrainerWithEndlines(): void
    {
        $listAdmin = <<<'LIST'
            toto,

            1212121212000000000000012,

            01234567890123456789011
            LIST;
        $listTrainer = <<<'LIST'
            titi,

            1212121212000000000000012,

            0123456789012345678901,
            11655986856658439236105875191
            LIST;
        $listCollector = <<<'LIST'
            tata,
            1212121212000000000000012,
            LIST;

        $authenticator = $this->getDiscordAuthenticator($listAdmin, $listTrainer, $listCollector);

        $request = $this->createMock(Request::class);

        $validationPassport = $authenticator->authenticate($request);

        $this->assertInstanceOf(SelfValidatingPassport::class, $validationPassport);

        /** @var User $user */
        $user = $validationPassport->getUser();
        $this->assertTrue($user->isAnAdmin());
        $this->assertTrue($user->isATrainer());
        $this->assertTrue($user->isACollector());
        $this->assertEquals('1212121212000000000000012', $user->getId());
        $this->assertEquals('1212121212000000000000012', $user->getUserIdentifier());
    }

    private function getDiscordAuthenticator(string $listAdmin, string $listTrainer, string $listCollector): DiscordAuthenticator
    {
        $oauth2Client = $this->createMock(OAuth2ClientInterface::class);
        $oauth2Client
            ->expects($this->once())
            ->method('getAccessToken')
            ->willReturn(new AccessToken([
                'access_token' => '1douze2',
            ]))
        ;
        $oauth2Client
            ->expects($this->once())
            ->method('fetchUserFromToken')
            ->willReturn(new GoogleUser([
                'sub' => '1212121212000000000000012',
                'name' => 'Douze',
            ]))
        ;

        $clientRegistry = $this->createMock(ClientRegistry::class);
        $clientRegistry
            ->expects($this->once())
            ->method('getClient')
            ->willReturn($oauth2Client)
        ;

        $router = $this->createMock(RouterInterface::class);

        return new DiscordAuthenticator(
            $clientRegistry,
            $router,
            $listAdmin,
            $listTrainer,
            $listCollector,
            true,
        );
    }
}
