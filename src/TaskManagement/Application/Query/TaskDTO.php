<?php

namespace TaskManagement\Application\Query;

class TaskDTO
{
    public function __construct(
        private string $taskId,
        private string $userId,
        private string $title,
        private string $description,
        private int $status,
        private string $date
    ) {
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
