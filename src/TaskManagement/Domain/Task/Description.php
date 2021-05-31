<?php


namespace TaskManagement\Domain\Task;


class Description
{
    public function __construct(private string $description)
    {
    }

    public static function fromString(string $description): self
    {
        return new self($description);
    }

    public function toString(): string
    {
        return $this->description;
    }
}