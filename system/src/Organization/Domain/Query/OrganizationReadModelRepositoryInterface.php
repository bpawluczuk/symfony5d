<?php

declare(strict_types=1);

namespace App\Organization\Domain\Query;

use App\User\Domain\Value\UserId;
use App\Location\Domain\Value\LocationId;

interface OrganizationReadModelRepositoryInterface
{
    public function getByName(string $name): ?OrganizationReadModelInterface;

    public function getUserOrganization(UserId $userId): ?OrganizationReadModelInterface;

    public function getLocationOrganization(LocationId $locationId): ?OrganizationReadModelInterface;
}