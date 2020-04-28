<?php

declare(strict_types=1);

namespace App\Organization\Ui\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

class NewOrganizationUser
{
    /**
     * @Assert\NotBlank
     * @SWG\Property(type="string", description="The unique username for the User")
     */
    public $username;

    /**
     * @Assert\NotBlank
     * @SWG\Property(type="string", description="Password")
     */
    public $password;

    /**
     * @Assert\NotBlank
     * @SWG\Property(type="string", description="Repeat password")
     */
    public $repeatPassword;
}