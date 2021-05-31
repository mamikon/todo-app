<?php


namespace TaskManagement\Domain\Task;


use Ramsey\Uuid\Uuid;

class TaskId
{
    private function __construct(private string $id)
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