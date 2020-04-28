<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\Organization\Domain\Value\OrganizationId;
use App\User\Domain\Factory\UserReadModelFactory;
use App\User\Domain\Query\UserReadModelInterface;
use App\User\Domain\Query\UserReadModelRepositoryInterface;
use App\User\Domain\Value\UserId;
use App\User\Domain\Value\Username;

class UserReadModelRepository implements UserReadModelRepositoryInterface
{
    private UserReadModelFactory $userReadModelFactory;

    public function __construct(UserReadModelFactory $userReadModelFactory)
    {
        $this->userReadModelFactory = $userReadModelFactory;
    }

    /**
     * @param OrganizationId $organizationId
     * @return UserReadModelInterface[]
     */
    public function getAllInOrganization(OrganizationId $organizationId): array
    {
        return [];
    }

    public function getByIdInOrganization(UserId $userId, OrganizationId $organizationId): ?UserReadModelInterface
    {
        return null;
    }

    public function getByUsernameInOrganization(Username $username, OrganizationId $organizationId): ?UserReadModelInterface
    {
        return null;
    }

    public function getByUsername(Username $username): ?UserReadModelInterface
    {
        return null;
    }
}