<?php

declare(strict_types=1);

namespace App\User\Domain\Query;

use App\Organization\Domain\Value\OrganizationId;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;

interface UserReadModelRepositoryInterface
{
    /**
     * @param OrganizationId $organizationId
     * @return UserReadModelInterface[]
     */
    public function getAllInOrganization(OrganizationId $organizationId): array;

    public function getByIdInOrganization(UserId $userId, OrganizationId $organizationId): ?UserReadModelInterface;

    public function getByUsernameInOrganization(Username $username, OrganizationId $organizationId): ?UserReadModelInterface;

    public function getByUsername(Username $username): ?UserReadModelInterface;
}