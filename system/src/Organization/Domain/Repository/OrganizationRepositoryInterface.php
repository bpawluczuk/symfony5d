<?php

declare(strict_types=1);

namespace App\Organization\Domain\Repository;

use App\Organization\Domain\Entity\Organization;
use App\Organization\Domain\Value\OrganizationId;

interface OrganizationRepositoryInterface
{
    public function getById(OrganizationId $organizationId): ?Organization;

    public function save(Organization $organization): void;
}