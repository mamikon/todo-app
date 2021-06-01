<?php


namespace TaskManagement\Domain\Task;


class Task
{


    private function __construct(private TaskId $taskId, private User $user, private Title $title, private Description $description, private Status $status)
    {
    }

    public static function create(TaskId $taskId, User $user, Title $title, Description $description, Status $status): self
    {
        return new self(
            taskId: $taskId,
            user: $user,
            title: $title,
            description: $description,
            status: $status
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
}