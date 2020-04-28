<?php

declare(strict_types=1);

namespace App\Auth\Ui\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

class LoginCredentials
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="6", max="32")
     * @SWG\Property(type="string", description="Username")
     */
    public string $username = '';

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="6", max="32")
     * @SWG\Property(type="string", description="Password")
     */
    public string $password = '';
}