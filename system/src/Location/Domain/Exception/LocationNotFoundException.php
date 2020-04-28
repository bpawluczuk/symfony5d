<?php

declare(strict_types=1);

namespace App\Location\Domain\Exception;

class LocationNotFoundException extends LocationException
{
    public $message = 'Location not found';
}