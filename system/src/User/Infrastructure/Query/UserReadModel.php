<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Query;

use App\User\Domain\Query\UserReadModelInterface;
use Swagger\Annotations as SWG;

class UserReadModel implements UserReadModelInterface
{
    /**
     * @SWG\Property(type="string", description="User Id")
     */
    private string $id;

    /**
     * @SWG\Property(type="string", description="Unique User's username")
     */
    private string $username;

    public function __construct(string $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}