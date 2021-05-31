<?php


namespace TaskManagement\Domain;


use Ramsey\Uuid\Nonstandard\Uuid;
use TaskManagement\Domain\Exception\InvalidUuidException;

class User
{
    private function __construct(private string $id)
    {

    }

    /**
     * @throws InvalidUuidException
     */
    public static function fromString(string $id): self
    {
        if (!Uuid::isValid($id)) {
            throw new InvalidUuidException(sprintf("Expected valid UUID. Given '%s' is invalid UUID.", $id));
        }

        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}