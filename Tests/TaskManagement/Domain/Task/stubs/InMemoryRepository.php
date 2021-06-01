<?php


namespace TaskManagement\Domain\Task\stubs;


use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskRepository;

class InMemoryRepository implements TaskRepository
{
    /**
     * @var Task[]
     */
    private array $tasks = [];

    public function store(Task $task): void
    {
        $this->tasks[$task->getTaskId()->toString()] = $task;
    }

    public function getById(TaskId $taskId): Task
    {
        return $this->tasks[$taskId->toString()];
    }
}