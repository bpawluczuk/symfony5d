<?php

declare(strict_types=1);

namespace App\Location\Domain\Query;

use App\Organization\Domain\Value\OrganizationId;
use App\Location\Domain\Value\LocationId;

interface LocationReadModelRepositoryInterface
{
    /**
     * @param OrganizationId $organizationId
     * @return LocationReadModelInterface[]
     */
    public function getAllInOrganization(OrganizationId $organizationId): array;

    public function getByIdInOrganization(LocationId $locationId, OrganizationId $organizationId): ?LocationReadModelInterface;
}