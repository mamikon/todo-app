<?php


namespace TaskManagement\Domain\Task;


interface TaskRepository
{
    public function store(Task $task): void;

    public function getById(TaskId $taskId): Task;

    /**
     * @return Task[]
     */
    public function getUsersTaskForGivenDate(User $user, Date $date): array;
}