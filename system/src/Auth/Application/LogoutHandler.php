<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\Auth\Domain\Exception\UnauthorizedLogoutException;
use App\Auth\Domain\Query\SessionReadModelRepositoryInterface;
use App\Auth\Domain\Repository\SessionRepositoryInterface;
use App\Shared\Application\HandlerInterface;
use App\User\Domain\Repository\UserRepositoryInterface;

class LogoutHandler implements HandlerInterface
{
    private UserRepositoryInterface $userRepositoryInterface;
    private SessionReadModelRepositoryInterface $sessionReadModelRepository;
    private SessionRepositoryInterface $sessionRepository;

    public function __construct(
        UserRepositoryInterface $userRepositoryInterface,
        SessionReadModelRepositoryInterface $sessionReadModelRepository,
        SessionRepositoryInterface $sessionRepository
    )
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->sessionReadModelRepository = $sessionReadModelRepository;
        $this->sessionRepository = $sessionRepository;
    }

    public function __invoke(LogoutCommand $command): void
    {
        $user = $this->userRepositoryInterface->getById($command->getUserId());
        $currentSession = $this->sessionReadModelRepository->getUserSession($user->getId());

        if (!$currentSession) {
            throw new UnauthorizedLogoutException();
        }

        $this->sessionRepository->destroyUserSession($user->getId());
    }
}
