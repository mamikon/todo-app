<?php


namespace TaskManagement\Domain;


use Ramsey\Uuid\Uuid;

class TaskId
{
    public function __construct(private string $id)
    {

    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->id;
    }
}