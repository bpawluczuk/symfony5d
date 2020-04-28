<?php

declare(strict_types=1);

namespace App\Project\Infrastructure\Query;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Query\ProjectReadModelInterface;
use App\Project\Domain\Query\ProjectReadModelRepositoryInterface;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;

class ProjectReadModelRepository implements ProjectReadModelRepositoryInterface
{
    public function getOneByIdInOrganization(ProjectId $projectId, OrganizationId $organizationId): ?ProjectReadModelInterface
    {
        return null;
    }

    public function getOneByNameInOrganization(ProjectName $projectName, OrganizationId $organizationId): ?ProjectReadModelInterface
    {
        return null;
    }

    public function getAllInOrganization(OrganizationId $organizationId): array
    {
        return [];
    }
}