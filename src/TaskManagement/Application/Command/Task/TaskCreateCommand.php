<?php

namespace TaskManagement\Application\Command\Task;

use DateTimeImmutable;
use TaskManagement\Domain\Task\Status;

class TaskCreateCommand
{
    public function __construct(
        private string $user,
        private string $title,
        private DateTimeImmutable $date,
        private string $description = '',
        private int|string $status = Status::DRAFT
    ) {
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): int|string
    {
        return $this->status;
    }
}
