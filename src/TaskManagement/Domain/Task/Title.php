<?php


namespace TaskManagement\Domain\Task;


use TaskManagement\Domain\Task\Exception\EmptyArgumentException;

class Title
{
    private function __construct(private string $title)
    {

    }

    public static function fromString(string $titleString): self
    {
        if ($titleString === "") {
            throw new EmptyArgumentException("Task Title can't be created from empty string");
        }

        return new self($titleString);
    }

    public function toString(): string
    {
        return $this->title;
    }
}