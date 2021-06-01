<?php


namespace TaskManagement\Domain\Task;


use Ramsey\Uuid\Uuid;
use TaskManagement\Domain\Task\Exception\InvalidUuidException;

class TaskId
{
    private function __construct(private string $id)
    {

    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        if (!Uuid::isValid($id)) {
            throw new InvalidUuidException(sprintf("Task Id can't be created invalid Uuid provided- %s", $id));
        }

        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}