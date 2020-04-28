<?php

declare(strict_types=1);

namespace App\Shared\Domain\Value;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AbstractId
{
    private UuidInterface $value;

    public function __construct(UuidInterface $id)
    {
        $this->value = $id;
    }

    public static function create(): self
    {
        return new static(Uuid::uuid4());
    }

    public static function createFromUuid(UuidInterface $uuid)
    {
        return new static($uuid);
    }

    public static function createFromString(string $id): self
    {
        $uuid = Uuid::fromString($id);

        return new static($uuid);
    }

    public function get(): UuidInterface
    {
        return $this->value;
    }

    public function equals(self $id): bool
    {
        return $this->value->equals($id->get());
    }

    public function toString(): string
    {
        return $this->value->toString();
    }

    public function __toString()
    {
        return $this->toString();
    }
}