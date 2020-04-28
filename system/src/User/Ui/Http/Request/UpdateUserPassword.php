<?php

declare(strict_types=1);

namespace App\User\Ui\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

class UpdateUserPassword
{
    /**
     * @Assert\NotBlank
     * @SWG\Property(type="string", description="User's old password")
     */
    public $oldPassword;

    /**
     * @Assert\NotBlank
     * @SWG\Property(type="string", description="User's new password")
     */
    public $newPassword;

    /**
     * @Assert\NotBlank
     * @SWG\Property(type="string", description="Repeat password")
     */
    public $repeatNewPassword;
}