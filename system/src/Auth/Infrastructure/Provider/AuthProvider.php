<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Provider;

use App\Auth\Infrastructure\Value\Auth;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Value\Username;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AuthProvider implements UserProviderInterface
{
    private UserRepositoryInterface $userRepository;
    private RequestStack $requestStack;

    public function __construct(UserRepositoryInterface $userRepository, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->userRepository->getByUsername(Username::create($username));

        return Auth::createFromEntity($user);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return Auth::class === $class;
    }
}