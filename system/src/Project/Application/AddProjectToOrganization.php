<?php

declare(strict_types=1);

namespace App\Project\Application;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Value\ProjectName;

class AddProjectToOrganization
{
    protected OrganizationId $organizationId;
    protected ProjectName $name;

    public function __construct(OrganizationId $organizationId, ProjectName $name)
    {
        $this->organizationId = $organizationId;
        $this->name = $name;
    }

    public function getOrganizationId(): OrganizationId
    {
        return $this->organizationId;
    }

    public function getProjectName(): ProjectName
    {
        return $this->name;
    }
}