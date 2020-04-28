<?php

declare(strict_types=1);

namespace App\Project\Domain\Exception;

class ProjectNotFoundException extends ProjectException
{
    protected $message = 'Project not found';
}