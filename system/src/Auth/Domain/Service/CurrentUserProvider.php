<?php

declare(strict_types=1);

namespace App\Auth\Domain\Service;

use App\Shared\Ui\Exception\UnauthorizedActionCallException;
use App\User\Domain\Query\UserReadModelInterface;
use App\User\Domain\Query\UserReadModelRepositoryInterface;
use App\User\Domain\Value\Username;
use Symfony\Component\Security\Core\Security;

class CurrentUserProvider
{
    private Security $security;
    private UserReadModelRepositoryInterface $userReadModelRepository;

    public function __construct(Security $security, UserReadModelRepositoryInterface $userReadModelRepository)
    {
        $this->security = $security;
        $this->userReadModelRepository = $userReadModelRepository;
    }

    public function getCurrentUser(): ?UserReadModelInterface
    {
        $currentUser = $this->security->getUser();

        if (!$currentUser) {
            throw new UnauthorizedActionCallException();
        }

        $username = Username::create($currentUser->getUsername());

        return $this->userReadModelRepository->getByUsername($username);
    }
}