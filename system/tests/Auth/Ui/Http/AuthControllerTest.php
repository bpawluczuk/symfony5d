<?php

declare(strict_types=1);

namespace App\Tests\Auth\Ui\Http;

use App\Auth\Domain\Service\CurrentUserProvider;
use App\Auth\Infrastructure\Query\SessionReadModel;
use App\Auth\Infrastructure\Query\SessionReadModelRepository;
use App\Tests\ItseApiTestCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Value\UserId;
use App\User\Infrastructure\Query\UserReadModel;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerTest extends ItseApiTestCase
{
    public function test_user_will_not_create_session_when_login_does_not_exist()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock
            ->expects($this->once())
            ->method('getByUsername')
            ->willReturn(null);

        self::$container->set(
            UserRepository::class,
            $userRepositoryMock,
        );

        $this->assertResponseCode(
            $this->post('/api/login', [
                'username' => 'username',
                'password' => 'password',
            ]),
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function test_user_will_not_create_session_with_bad_credentials()
    {
        $userMock = $this->createMock(User::class);

        $userMock
            ->expects($this->once())
            ->method('checkPassword')
            ->willReturn(false);

        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock
            ->expects($this->once())
            ->method('getByUsername')
            ->willReturn($userMock);

        self::$container->set(
            UserRepository::class,
            $userRepositoryMock,
        );

        $this->assertResponseCode(
            $this->post('/api/login', [
                'username' => 'username',
                'password' => 'password',
            ]),
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function test_user_will_create_session()
    {
        $userMock = $this->createMock(User::class);

        $userMock
            ->expects($this->once())
            ->method('checkPassword')
            ->willReturn(true);

        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock
            ->expects($this->any())
            ->method('getByUsername')
            ->willReturn($userMock);

        self::$container->set(
            UserRepository::class,
            $userRepositoryMock,
        );

        $this->assertResponse(
            $this->post('/api/login', [
                'username' => 'username',
                'password' => 'password',
            ]),
            'session',
            Response::HTTP_CREATED,
        );
    }

    public function test_user_will_not_logout_when_does_not_have_active_session()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userMock = $this->createMock(User::class);

        $userMock
            ->expects($this->any())
            ->method('getId')
            ->willReturn(UserId::create());

        $userRepositoryMock
            ->expects($this->once())
            ->method('getById')
            ->willReturn($userMock);

        self::$container->set(
            UserRepository::class,
            $userRepositoryMock,
        );

        $currentUserProviderMock = $this->createMock(CurrentUserProvider::class);
        $userReadModelMock = $this->createMock(UserReadModel::class);

        $userReadModelMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn(UserId::create()->toString());

        $currentUserProviderMock
            ->expects($this->once())
            ->method('getCurrentUser')
            ->willReturn($userReadModelMock);

        self::$container->set(
            CurrentUserProvider::class,
            $currentUserProviderMock,
        );

        $sessionReadModelRepositoryMock = $this->createMock(SessionReadModelRepository::class);

        $sessionReadModelRepositoryMock
            ->expects($this->once())
            ->method('getUserSession')
            ->willReturn(null);

        self::$container->set(
            SessionReadModelRepository::class,
            $sessionReadModelRepositoryMock,
        );

        $this->assertResponseCode(
            $this->delete('/api/logout'),
            Response::HTTP_UNAUTHORIZED,
        );
    }

    public function test_user_will_logout()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userMock = $this->createMock(User::class);

        $userMock
            ->expects($this->any())
            ->method('getId')
            ->willReturn(UserId::create());

        $userRepositoryMock
            ->expects($this->once())
            ->method('getById')
            ->willReturn($userMock);

        self::$container->set(
            UserRepository::class,
            $userRepositoryMock,
        );

        $currentUserProviderMock = $this->createMock(CurrentUserProvider::class);
        $userReadModelMock = $this->createMock(UserReadModel::class);

        $userReadModelMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn(UserId::create()->toString());

        $currentUserProviderMock
            ->expects($this->once())
            ->method('getCurrentUser')
            ->willReturn($userReadModelMock);

        self::$container->set(
            CurrentUserProvider::class,
            $currentUserProviderMock,
        );

        $sessionReadModelRepositoryMock = $this->createMock(SessionReadModelRepository::class);
        $sessionReadModelMock = $this->createMock(SessionReadModel::class);

        $sessionReadModelRepositoryMock
            ->expects($this->once())
            ->method('getUserSession')
            ->willReturn($sessionReadModelMock);

        self::$container->set(
            SessionReadModelRepository::class,
            $sessionReadModelRepositoryMock,
        );

        $this->assertResponseCode(
            $this->delete('/api/logout'),
            Response::HTTP_NO_CONTENT,
        );
    }
}