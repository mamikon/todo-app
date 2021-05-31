<?php


namespace TaskManagement\Domain\Task;


class Status
{

    const INCOMPLETE = 1;


    public function __construct(private int $status)
    {

    }

    public static function fromInt(int $status)
    {
        return new self($status);
    }
}