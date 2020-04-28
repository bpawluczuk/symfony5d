<?php

declare(strict_types=1);

namespace App\Tests\Organization\Ui\Http;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Infrastructure\Query\OrganizationReadModel;
use App\Organization\Infrastructure\Query\OrganizationReadModelRepository;
use App\Organization\Infrastructure\Repository\OrganizationRepository;
use App\Tests\ItseApiTestCase;
use App\User\Domain\Query\UserReadModelInterface;
use App\User\Infrastructure\Query\UserReadModelRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class OrganizationControllerTest extends ItseApiTestCase
{
    public function testWillNotFetchItsOrganizationWhenNotAMemberOfAny()
    {
        $organizationReadModelRepositoryMock = $this->createMock(OrganizationReadModelRepository::class);

        $organizationReadModelRepositoryMock
            ->expects($this->once())
            ->method('getUserOrganization')
            ->willReturn(null);

        self::$container->set(
            OrganizationReadModelRepository::class,
            $organizationReadModelRepositoryMock
        );

        $this->assertResponseCode(
            $this->get('/api/organizations'),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testUserCanFetchOrganizationItBelongsTo()
    {
        $organizationReadModelRepositoryMock = $this->createMock(OrganizationReadModelRepository::class);

        $organizationReadModelRepositoryMock
            ->expects($this->once())
            ->method('getUserOrganization')
            ->willReturn($this->createEmptyReadModel());

        self::$container->set(
            OrganizationReadModelRepository::class,
            $organizationReadModelRepositoryMock
        );

        $this->assertResponse(
            $this->get('/api/organizations'),
            'user_organization',
        );
    }

    public function testUserWilNotBeCreatedWhenOrganizationDoesNotExist()
    {
        $organizationRepository = $this->createMock(OrganizationRepository::class);

        $organizationRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(null);

        self::$container->set(
            OrganizationRepository::class,
            $organizationRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/organizations/%s/users', Uuid::uuid4()->toString()), [
                'username' => 'example@example.com',
                'password' => 'itse',
                'repeatPassword' => 'itse',
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testUserWillNotBeBeCreatedWhenAlreadyExists()
    {
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationMock = $this->createMock(Organization::class);

        $organizationRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($organizationMock);

        self::$container->set(
            OrganizationRepository::class,
            $organizationRepository,
        );

        $userReadModelRepository = $this->createMock(UserReadModelRepository::class);
        $userReadModelMock = $this->createMock(UserReadModelInterface::class);

        $userReadModelRepository
            ->expects($this->once())
            ->method('getByUsername')
            ->willReturn($userReadModelMock);

        self::$container->set(
            UserReadModelRepository::class,
            $userReadModelRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/organizations/%s/users', Uuid::uuid4()->toString()), [
                'username' => 'example@example.com',
                'password' => 'itse',
                'repeatPassword' => 'itse',
            ]),
            Response::HTTP_CONFLICT,
        );
    }

    public function testUserWillBeCreatedInTheOrganization()
    {
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationMock = $this->createMock(Organization::class);

        $organizationRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn($organizationMock);

        self::$container->set(
            OrganizationRepository::class,
            $organizationRepository,
        );

        $userReadModelRepository = $this->createMock(UserReadModelRepository::class);

        $userReadModelRepository
            ->expects($this->once())
            ->method('getByUsername')
            ->willReturn(null);

        self::$container->set(
            UserReadModelRepository::class,
            $userReadModelRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/organizations/%s/users', Uuid::uuid4()->toString()), [
                'username' => 'username',
                'password' => 'password',
                'repeatPassword' => 'repeatPassword',
            ]),
            Response::HTTP_CREATED,
        );
    }

    public function testFormErrorsWillBeReturnedWhenBadDataFormatDuringUserCreation()
    {
        $this->assertResponseCode(
            $this->post(sprintf('/api/organizations/%s/users', Uuid::uuid4()->toString()), [
                'bullshit' => 'data',
            ]),
            Response::HTTP_BAD_REQUEST,
        );
    }

    private function createEmptyReadModel(): OrganizationReadModel
    {
        return new OrganizationReadModel(
            'id',
            'name'
        );
    }
}