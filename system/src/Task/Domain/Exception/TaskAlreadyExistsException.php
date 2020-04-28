<?php

declare(strict_types=1);

namespace App\Task\Domain\Exception;

class TaskAlreadyExistsException extends TaskException
{
    protected $message = 'Task already exists';
}