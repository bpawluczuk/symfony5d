<?php

declare(strict_types=1);

namespace App\Project\Application;

use App\Organization\Domain\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\Project\Domain\Exception\ProjectAlreadyExistsException;
use App\Project\Domain\Factory\ProjectFactory;
use App\Project\Domain\Query\ProjectReadModelRepositoryInterface;
use App\Shared\Application\HandlerInterface;

class AddProjectToOrganizationHandler implements HandlerInterface
{
    protected OrganizationRepositoryInterface $organizationRepository;
    protected ProjectReadModelRepositoryInterface $projectReadModelRepository;
    protected ProjectFactory $projectFactory;

    public function __construct(
        OrganizationRepositoryInterface $organizationRepository,
        ProjectReadModelRepositoryInterface $projectReadModelRepository,
        ProjectFactory $projectFactory
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->projectReadModelRepository = $projectReadModelRepository;
        $this->projectFactory = $projectFactory;
    }

    public function __invoke(AddProjectToOrganization $addProjectToOrganization): void
    {
        $organization = $this->organizationRepository->getById($addProjectToOrganization->getOrganizationId());

        if (!$organization) {
            throw new OrganizationNotFoundException();
        }

        $existingProject = $this->projectReadModelRepository->getOneByNameInOrganization(
            $addProjectToOrganization->getProjectName(),
            $addProjectToOrganization->getOrganizationId()
        );

        if ($existingProject) {
            throw new ProjectAlreadyExistsException();
        }

        $project = $this->projectFactory->createProject(
            $addProjectToOrganization->getOrganizationId(),
            $addProjectToOrganization->getProjectName()
        );


        $organization->addProject($project);
        $this->organizationRepository->save($organization);
    }
}