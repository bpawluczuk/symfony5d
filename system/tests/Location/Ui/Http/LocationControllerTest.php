<?php

declare(strict_types=1);

namespace App\Tests\Location\Ui\Http;

use App\Tests\ItseApiTestCase;
use App\Organization\Domain\Entity\Organization;
use App\Organization\Infrastructure\Repository\OrganizationRepository;
use App\Location\Domain\Query\LocationReadModelInterface;
use App\Location\Infrastructure\Query\LocationReadModel;
use App\Location\Infrastructure\Query\LocationReadModelRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class LocationControllerTest extends ItseApiTestCase
{
    public function testAllLocationsWillNotBeFetchedWhenNotFilteredByOrganization()
    {
        $this->assertResponseCode(
            $this->get('/api/locations'),
            Response::HTTP_BAD_REQUEST,
        );
    }

    public function testAllLocationsWillNotBeFetchedWhenNoLocationsInTheOrganization()
    {
        $locationReadModelRepository = $this->createMock(LocationReadModelRepository::class);

        $locationReadModelRepository
            ->expects($this->once())
            ->method('getAllInOrganization')
            ->willReturn([]);

        self::$container->set(
            LocationReadModelRepository::class,
            $locationReadModelRepository,
        );

        $this->assertResponseCode(
            $this->get('/api/locations', [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testAllLocationsAreFetched()
    {
        $locationReadModelRepository = $this->createMock(LocationReadModelRepository::class);

        $locationReadModelRepository
            ->expects($this->once())
            ->method('getAllInOrganization')
            ->willReturn([
                $this->createEmptyReadModel(),
                $this->createEmptyReadModel(),
            ]);

        self::$container->set(
            LocationReadModelRepository::class,
            $locationReadModelRepository,
        );

        $this->assertResponse(
            $this->get('/api/locations', [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            'locations_all',
        );
    }

    public function testLocationWillNotBeFetchedWhenNotFilteredByOrganization()
    {
        $this->assertResponseCode(
            $this->get(sprintf('/api/locations/%s', Uuid::uuid4()->toString())),
            Response::HTTP_BAD_REQUEST,
        );
    }

    public function testLocationWillNotBeFetchedWhenDoesNotExist()
    {
        $locationReadModelRepository = $this->createMock(LocationReadModelRepository::class);

        $locationReadModelRepository
            ->expects($this->once())
            ->method('getByIdInOrganization')
            ->willReturn(null);

        self::$container->set(
            LocationReadModelRepository::class,
            $locationReadModelRepository,
        );

        $this->assertResponseCode(
            $this->get(sprintf('/api/locations/%s', Uuid::uuid4()->toString()), [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testLocationCanBeFetchedById()
    {
        $locationReadModelRepository = $this->createMock(LocationReadModelRepository::class);

        $locationReadModelRepository
            ->expects($this->once())
            ->method('getByIdInOrganization')
            ->willReturn($this->createEmptyReadModel());

        self::$container->set(
            LocationReadModelRepository::class,
            $locationReadModelRepository,
        );

        $this->assertResponse(
            $this->get(sprintf('/api/locations/%s', Uuid::uuid4()->toString()), [
                'organizationId' => Uuid::uuid4()->toString()
            ]),
            'location_by_id',
        );
    }

    public function testLocationWilNotBeCreatedWhenOrganizationDoesNotExist()
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
            $this->post(sprintf('/api/locations/organizations/%s', Uuid::uuid4()->toString()), [
            ]),
            Response::HTTP_NOT_FOUND,
        );
    }

    public function testLocationWillNotBeBeCreatedWhenAlreadyExists()
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

        $locationReadModelRepository = $this->createMock(LocationReadModelRepository::class);
        $locationReadModelMock = $this->createMock(LocationReadModelInterface::class);

        $locationReadModelRepository
            ->expects($this->once())
            ->method('getByIdInOrganization')
            ->willReturn($locationReadModelMock);

        self::$container->set(
            LocationReadModelRepository::class,
            $locationReadModelRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/locations/organizations/%s', Uuid::uuid4()->toString()), [
            ]),
            Response::HTTP_CONFLICT,
        );
    }

    public function testLocationWillBeCreatedInTheOrganization()
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

        $locationReadModelRepository = $this->createMock(LocationReadModelRepository::class);

        $locationReadModelRepository
            ->expects($this->once())
            ->method('getByIdInOrganization')
            ->willReturn(null);

        self::$container->set(
            LocationReadModelRepository::class,
            $locationReadModelRepository,
        );

        $this->assertResponseCode(
            $this->post(sprintf('/api/locations/organizations/%s', Uuid::uuid4()->toString()), [
            ]),
            Response::HTTP_CREATED,
        );
    }

    public function testFormErrorsWillBeReturnedWhenBadDataFormatDuringLocationCreation()
    {
        $this->assertResponseCode(
            $this->post(sprintf('/api/locations/organizations/%s', Uuid::uuid4()->toString()), [
                'bamboozled' => 'data',
            ]),
            Response::HTTP_BAD_REQUEST,
        );
    }

    /**
     * This method exists because there is some problem with mocking ReadModel. When model grows it is also
     * required to be updated.
     * @return LocationReadModelInterface
     */
    private function createEmptyReadModel(): LocationReadModelInterface
    {
        return new LocationReadModel(
            'id'
        );
    }
}