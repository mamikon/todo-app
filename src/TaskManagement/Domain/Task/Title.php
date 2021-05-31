<?php


namespace TaskManagement\Domain\Task;


class Title
{
    public function __construct(private string $title)
    {

    }

    public static function fromString(string $titleString): self
    {
        return new self($titleString);
    }

    public function toString(): string
    {
        return $this->title;
    }
}