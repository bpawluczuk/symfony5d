<?php

declare(strict_types=1);

namespace App\Organization\Application;

use App\Organization\Domain\Exception\OrganizationAlreadyExistsException;
use App\Organization\Domain\Factory\OrganizationFactory;
use App\Organization\Domain\Query\OrganizationReadModelRepositoryInterface;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\Shared\Application\HandlerInterface;

class CreateOrganizationHandler implements HandlerInterface
{
    private OrganizationReadModelRepositoryInterface $organizationReadModelRepository;
    private OrganizationFactory $organizationFactory;
    private OrganizationRepositoryInterface $organizationRepository;

    public function __construct(
        OrganizationReadModelRepositoryInterface $organizationReadModelRepository,
        OrganizationFactory $organizationFactory,
        OrganizationRepositoryInterface $organizationRepository
    )
    {
        $this->organizationReadModelRepository = $organizationReadModelRepository;
        $this->organizationFactory = $organizationFactory;
        $this->organizationRepository = $organizationRepository;
    }

    public function __invoke(CreateOrganizationCommand $command)
    {
        $existingOrganization = $this->organizationReadModelRepository->getByName($command->getName());

        if ($existingOrganization) {
            throw new OrganizationAlreadyExistsException();
        }

        $newOrganization = $this->organizationFactory->createOrganization(
            $command->getName(),
            $command->getLogoUrl(),
        );

        $this->organizationRepository->save($newOrganization);
    }
}