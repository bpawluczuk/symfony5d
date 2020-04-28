<?php

declare(strict_types=1);

namespace App\Organization\Infrastructure\Query;

use App\Organization\Domain\Factory\OrganizationReadModelFactory;
use App\Organization\Domain\Query\OrganizationReadModelInterface;
use App\Organization\Domain\Query\OrganizationReadModelRepositoryInterface;
use App\User\Domain\Value\UserId;
use App\Location\Domain\Value\LocationId;

class OrganizationReadModelRepository implements OrganizationReadModelRepositoryInterface
{
    private OrganizationReadModelFactory $organizationReadModelFactory;

    public function __construct(OrganizationReadModelFactory $organizationReadModelFactory)
    {
        $this->organizationReadModelFactory = $organizationReadModelFactory;
    }

    public function getByName(string $name): ?OrganizationReadModelInterface
    {
        return null;
    }

    public function getUserOrganization(UserId $userId): ?OrganizationReadModelInterface
    {
        return null;
    }

    public function getLocationOrganization(LocationId $locationId): ?OrganizationReadModelInterface
    {
        return null;
    }
}