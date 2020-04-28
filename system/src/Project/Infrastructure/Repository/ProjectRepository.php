<?php

declare(strict_types=1);

namespace App\Project\Infrastructure\Repository;

use App\Organization\Domain\Value\OrganizationId;
use App\Project\Domain\Entity\Project;
use App\Project\Domain\Repository\ProjectRepositoryInterface;
use App\Project\Domain\Value\ProjectId;
use App\Project\Domain\Value\ProjectName;
use Doctrine\ORM\EntityManagerInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getById(ProjectId $projectId): ?Project
    {
        return null;
    }

    public function getOneByIdInOrganization(ProjectId $projectId, OrganizationId $organizationId): ?Project
    {
        return null;
    }

    public function getOneByNameInOrganization(ProjectName $projectName, OrganizationId $organizationId): ?Project
    {
        return null;
    }

    public function save(Project $project): void
    {
        $this->entityManager->persist($project);
    }

    public function remove(Project $project): void
    {
        $this->entityManager->remove($project);
    }

}