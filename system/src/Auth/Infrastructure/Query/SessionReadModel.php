<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Query;

use App\Auth\Domain\Query\SessionReadModelInterface;
use Swagger\Annotations as SWG;

class SessionReadModel implements SessionReadModelInterface
{
    /**
     * @SWG\Property(type="string", description="Session Id")
     */
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}