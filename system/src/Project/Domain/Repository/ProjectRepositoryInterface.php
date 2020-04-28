<?php

declare(strict_types=1);

namespace App\Project\Domain\Repository;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Entity\Project;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;

interface ProjectRepositoryInterface
{
    public function getById(ProjectId $projectId): ?Project;

    public function getOneByIdInOrganization(ProjectId $projectId, OrganizationId $organizationId): ?Project;

    public function getOneByNameInOrganization(ProjectName $projectName, OrganizationId $organizationId): ?Project;

    public function save(Project $project): void;

    public function remove(Project $project): void;
}