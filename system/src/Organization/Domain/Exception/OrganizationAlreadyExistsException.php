<?php

declare(strict_types=1);

namespace App\Organization\Domain\Exception;

class OrganizationAlreadyExistsException extends OrganizationException
{
    public $message = 'Organization already exists';
}