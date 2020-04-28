<?php

declare(strict_types=1);

namespace App\Shared\Ui\Exception;

use App\Shared\Domain\Exception\PresentableException;

class UnauthorizedActionCallException extends PresentableException
{
    public $message = 'Unauthorized Action Call';
}