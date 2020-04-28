<?php

declare(strict_types=1);

namespace App\Project\Domain\Exception;

class ProjectAlreadyExistsException extends ProjectException
{
    protected $message = 'Project already exists';
}