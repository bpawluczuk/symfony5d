<?php

declare(strict_types=1);

namespace App\Project\Domain\Factory;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Entity\Project;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;

class ProjectFactory
{
    public function createProject(OrganizationId $organizationId, ProjectName $name): Project
    {
        return new Project(
            ProjectId::create(),
            $organizationId,
            $name->getName()
        );
    }
}