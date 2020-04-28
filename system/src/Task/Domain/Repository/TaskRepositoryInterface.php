<?php

declare(strict_types=1);

namespace App\Task\Domain\Repository;

use App\Task\Domain\Entity\Task;
use App\Task\Domain\Value\TaskId;

interface TaskRepositoryInterface
{
    public function getAll(): array;

    public function getById(TaskId $taskId): ?Task;

    public function getByName(string $name): ?Task;

    public function save(Task $task): void;
}