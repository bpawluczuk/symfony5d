<?php

declare(strict_types=1);

namespace App\Organization\Application;

use App\Organization\Domain\Exception\OrganizationNotFoundException;
use App\Organization\Domain\Repository\OrganizationRepositoryInterface;
use App\Shared\Application\HandlerInterface;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Factory\UserFactory;
use App\User\Domain\Query\UserReadModelRepositoryInterface;

class AddUserToOrganizationHandler implements HandlerInterface
{
    private OrganizationRepositoryInterface $organizationRepository;
    private UserReadModelRepositoryInterface $userReadModelRepository;
    private UserFactory $userFactory;

    public function __construct(
        OrganizationRepositoryInterface $organizationRepository,
        UserReadModelRepositoryInterface $userReadModelRepository,
        UserFactory $userFactory
    )
    {
        $this->organizationRepository = $organizationRepository;
        $this->userReadModelRepository = $userReadModelRepository;
        $this->userFactory = $userFactory;
    }

    public function __invoke(AddUserToOrganizationCommand $command)
    {
        $organization = $this->organizationRepository->getById($command->getOrganizationId());

        if (!$organization) {
            throw new OrganizationNotFoundException();
        }

        $existingUser = $this->userReadModelRepository->getByUsername($command->getUsername());

        if ($existingUser) {
            throw new UserAlreadyExistsException();
        }

        $newUser = $this->userFactory->createUser(
            $command->getUsername(),
            $command->getPassword()
        );

        $organization->addUser($newUser);

        $this->organizationRepository->save($organization);
    }
}