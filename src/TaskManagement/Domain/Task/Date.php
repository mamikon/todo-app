<?php


namespace TaskManagement\Domain\Task;


class Date
{
    private function __construct(private \DateTimeImmutable $dateTime)
    {

    }

    public static function create(\DateTimeImmutable $dateTime): self
    {
        return new self($dateTime);
    }

    public function toString(string $format = "Y-m-d"): string
    {
        return $this->dateTime->format($format);
    }
}