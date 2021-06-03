<?php


namespace TaskManagement\Domain\Task;


use TaskManagement\Domain\Task\Exception\TaskNotFoundException;

class TaskService
{


    public function __construct(private TaskRepository $repository)
    {
    }

    public function store(Task $task): void
    {
        $this->repository->store($task);
    }

    public function getById(TaskId $taskId): Task
    {
        return $this->repository->getById($taskId);
    }

    /**
     * @throws TaskNotFoundException
     */
    public function update(Task $task): void
    {
        $this->repository->update($task);
    }
}