<?php

declare(strict_types=1);

namespace App\Task\Domain\Exception;

class TaskNotFoundException extends TaskException
{
    protected $message = 'Task not found';
}