<?php


namespace TaskManagement\Application\Command\Task;


class TaskUpdateCommand
{
    public function __construct(
        private string $taskId,
        private ?string $user = null,
        private ?string $title = null,
        private ?\DateTimeImmutable $date = null,
        private ?string $description = null,
        private ?int $status = null)
    {
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }
}