<?php

declare(strict_types=1);

namespace App\Location\Domain\Exception;

class LocationAlreadyExistsException extends LocationException
{
    public $message = 'Location already exists';
}