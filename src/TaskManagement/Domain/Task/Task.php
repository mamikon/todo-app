<?php

namespace TaskManagement\Domain\Task;

class Task
{
    private function __construct(
        private TaskId $taskId,
        private User $user,
        private Title $title,
        private Description $description,
        private Status $status,
        private Date $date
    ) {
    }

    public static function create(
        TaskId $taskId,
        User $user,
        Title $title,
        Description $description,
        Status $status,
        Date $date
    ): self {
        return new self(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setTitle(Title $title): void
    {
        $this->title = $title;
    }

    public function setStatus(Status $status): void
    {
        $this->status->check($status);
        $this->status = $status;
    }

    public function setDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function setDate(Date $date): void
    {
        $this->date = $date;
    }
}
