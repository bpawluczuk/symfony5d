<?php

declare(strict_types=1);

namespace App\Organization\Domain\Exception;

class OrganizationNotFoundException extends OrganizationException
{
    public $message = 'Organization not found';
}