<?php


namespace TaskManagement\Domain;


class User
{
    private function __construct(private string $id)
    {

    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}