<?php

declare(strict_types=1);

namespace App\Task\Domain\Exception;

class AssignmentUserWithTaskAlreadyExistsException extends TaskException
{
    protected $message = 'Assignment User with Task already exists';
}