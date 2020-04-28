<?php

declare(strict_types=1);

namespace App\Location\Infrastructure\Query;

use App\Location\Domain\Factory\LocationReadModelFactory;
use App\Location\Domain\Query\LocationReadModelInterface;
use App\Location\Domain\Query\LocationReadModelRepositoryInterface;
use App\Location\Domain\Value\LocationId;
use App\Organization\Domain\Value\OrganizationId;

class LocationReadModelRepository implements LocationReadModelRepositoryInterface
{
    private LocationReadModelFactory $locationReadModelFactory;

    public function __construct(LocationReadModelFactory $locationReadModelFactory)
    {
        $this->locationReadModelFactory = $locationReadModelFactory;
    }

    /**
     * @param OrganizationId $organizationId
     * @return LocationReadModelRepositoryInterface[]
     */
    public function getAllInOrganization(OrganizationId $organizationId): array
    {
        return null;
    }

    public function getByIdInOrganization(LocationId $locationId, OrganizationId $organizationId): ?LocationReadModelInterface
    {
        return null;
    }
}