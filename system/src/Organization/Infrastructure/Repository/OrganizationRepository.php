<?php

declare(strict_types=1);

namespace App\Organization\Infrastructure\Repository;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Factory\OrganizationFactory;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\Organization\Domain\Value\OrganizationId;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    private OrganizationFactory $organizationFactory;

    public function __construct(OrganizationFactory $organizationFactory)
    {
        $this->organizationFactory = $organizationFactory;
    }

    public function getById(OrganizationId $organizationId): ?Organization
    {
        return null;
    }

    public function save(Organization $organization): void
    {

    }
}