<?php


namespace TaskManagement\Domain\Task;


use TaskManagement\Domain\Task\Exception\TaskNotFoundException;

interface TaskRepository
{
    public function store(Task $task): void;

    public function getById(TaskId $taskId): Task;

    /**
     * @return Task[]
     */
    public function getUserTasksForGivenDate(User $user, Date $date): array;

    /**
     * @throws TaskNotFoundException
     */
    public function update(Task $task): void;

    public function getUserTasks(User $user): array;
}