<?php

declare(strict_types=1);

namespace App\Task\Domain\Entity;

use App\Project\Domain\Value\ProjectId;
use App\Task\Domain\Value\TaskId;
use App\User\Domain\Entity\User;
use App\User\Domain\Value\UserId;
use Ramsey\Uuid\UuidInterface;

class Task
{
    private UuidInterface $id;
    private UuidInterface $projectId;
    private string $name;
    private array $users;

    public function __construct(
        TaskId $id,
        ProjectId $projectId,
        string $name
    )
    {
        $this->id = $id->get();
        $this->projectId = $projectId->get();
        $this->name = $name;
    }

    public function getId(): TaskId
    {
        return TaskId::createFromUuid($this->id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addUser(User $user): void
    {
        $this->users[] = $user;
    }

    public function hasUser(UserId $userId): bool
    {
        return array_reduce($this->users, function (User $user) use ($userId) {
            return $userId->equals($user->getId());
        }, false);
    }

}