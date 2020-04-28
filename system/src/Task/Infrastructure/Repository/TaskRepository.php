<?php

declare(strict_types=1);

namespace App\Task\Infrastructure\Repository;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Repository\TaskRepositoryInterface;
use App\Task\Domain\Value\TaskId;
use Doctrine\ORM\EntityManagerInterface;

class TaskRepository implements TaskRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function getAll(): array
    {

    }

    public function getById(TaskId $taskId): ?Task
    {

    }

    public function getByName(string $name): ?Task
    {

    }

    public function save(Task $task): void
    {

    }
}