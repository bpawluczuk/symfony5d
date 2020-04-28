<?php

declare(strict_types=1);

namespace App\Tests\User\Ui\Http;

use App\Tests\ItseApiTestCase;
use App\User\Domain\Query\UserReadModelInterface;
use App\User\Infrastructure\Query\UserReadModel;
use App\User\Infrastructure\Query\UserReadModelRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends ItseApiTestCase
{
    public function testAllUsersWillNotBeFetchedWhenNoUsersInTheOrganization()
    {
        $userReadModelRepository = $this->createMock(UserReadModelRepository::class);

        $userReadModelRepository
            ->expects($this->once())
            ->method('getAllInOrganization')
            ->willReturn([]);

        self::$container->set(
            UserReadModelRepository::class,
            $userReadModelRepository,
        );

        $this->assertResponseCode(
            $this->get(sprintf('/api/organizations/%s/users', Uuid::uuid4()->toString()), [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testAllUsersAreFetched()
    {
        $userReadModelRepository = $this->createMock(UserReadModelRepository::class);

        $userReadModelRepository
            ->expects($this->once())
            ->method('getAllInOrganization')
            ->willReturn([
                $this->createEmptyReadModel(),
                $this->createEmptyReadModel(),
                $this->createEmptyReadModel(),
            ]);

        self::$container->set(
            UserReadModelRepository::class,
            $userReadModelRepository,
        );

        $this->assertResponse(
            $this->get(sprintf('/api/organizations/%s/users', Uuid::uuid4()->toString()), [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            'users_all',
        );
    }

    public function testUserWillNotBeFetchedWhenDoesNotExist()
    {
        $userReadModelRepository = $this->createMock(UserReadModelRepository::class);

        $userReadModelRepository
            ->expects($this->once())
            ->method('getByIdInOrganization')
            ->willReturn(null);

        self::$container->set(
            UserReadModelRepository::class,
            $userReadModelRepository,
        );

        $this->assertResponseCode(
            $this->get(sprintf(
                '/api/organizations/%s/users/%s',
                Uuid::uuid4()->toString(),
                Uuid::uuid4()->toString()
            ), [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testUserCanBeFetchedById()
    {
        $userReadModelRepository = $this->createMock(UserReadModelRepository::class);

        $userReadModelRepository
            ->expects($this->once())
            ->method('getByIdInOrganization')
            ->willReturn($this->createEmptyReadModel());

        self::$container->set(
            UserReadModelRepository::class,
            $userReadModelRepository,
        );

        $this->assertResponse(
            $this->get(sprintf(
                '/api/organizations/%s/users/%s',
                Uuid::uuid4()->toString(),
                Uuid::uuid4()->toString()
            ), [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            'user_by_id',
        );
    }

    /**
     * This method exists because there is some problem with mocking ReadModel. When model grows it is also
     * required to be updated.
     * @return UserReadModelInterface
     */
    private function createEmptyReadModel(): UserReadModelInterface
    {
        return new UserReadModel(
            'id',
            'name'
        );
    }
}