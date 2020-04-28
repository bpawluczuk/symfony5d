<?php

declare(strict_types=1);

namespace App\Location\Application;

use App\Organization\Domain\Exception\OrganizationNotFoundException;
use App\Location\Domain\Exception\LocationAlreadyExistsException;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\Shared\Application\HandlerInterface;
use App\Location\Domain\Factory\LocationFactory;
use App\Location\Domain\Query\LocationReadModelRepositoryInterface;

class AddLocationToOrganizationHandler implements HandlerInterface
{
    private OrganizationRepositoryInterface $organizationRepository;
    private LocationReadModelRepositoryInterface $locationReadModelRepository;
    private LocationFactory $locationFactory;

    public function __construct(
        OrganizationRepositoryInterface $organizationRepository,
        LocationReadModelRepositoryInterface $locationReadModelRepository,
        LocationFactory $locationFactory
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->locationReadModelRepository = $locationReadModelRepository;
        $this->locationFactory = $locationFactory;
    }

    public function __invoke(AddLocationToOrganizationCommand $command)
    {
        $organization = $this->organizationRepository->getById($command->getOrganizationId());

        if (!$organization) {
            throw new OrganizationNotFoundException();
        }

        $existingLocation = $this->locationReadModelRepository->getByIdInOrganization(
            $command->getLocationId(),
            $command->getOrganizationId(),
        );

        if ($existingLocation) {
            throw new LocationAlreadyExistsException();
        }

        $newOrganizationLocation = $this->locationFactory->createLocation(
            $command->getOrganizationId(),
        );

        $organization->addLocation($newOrganizationLocation);

        $this->organizationRepository->save($organization);
    }


}