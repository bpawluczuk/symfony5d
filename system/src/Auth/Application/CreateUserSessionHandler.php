<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\Auth\Domain\Repository\SessionRepositoryInterface;
use App\Shared\Application\HandlerInterface;
use App\User\Domain\Repository\UserRepositoryInterface;

class CreateUserSessionHandler implements HandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private SessionRepositoryInterface $sessionRepository;

    public function __construct(UserRepositoryInterface $userRepository, SessionRepositoryInterface $sessionRepository)
    {
        $this->userRepository = $userRepository;
        $this->sessionRepository = $sessionRepository;
    }

    public function __invoke(CreateUserSessionCommand $command)
    {
        $user = $this->userRepository->getByUsername($command->getUsername());

        $this->sessionRepository->createSessionForUser(
            $user->getId(),
            $command->getSessionId(),
        );
    }
}
