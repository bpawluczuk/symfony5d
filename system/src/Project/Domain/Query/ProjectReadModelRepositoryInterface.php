<?php

declare(strict_types=1);

namespace App\Project\Domain\Query;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;

interface ProjectReadModelRepositoryInterface
{
    public function getOneByIdInOrganization(ProjectId $id, OrganizationId $organizationId): ?ProjectReadModelInterface;

    public function getOneByNameInOrganization(ProjectName $projectName, OrganizationId $organizationId): ?ProjectReadModelInterface;

    public function getAllInOrganization(OrganizationId $organizationId): array;
}