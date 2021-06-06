<?php


namespace TaskManagement\Domain\Task\stubs;


use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskRepository;
use TaskManagement\Domain\Task\User;

class InMemoryRepository implements TaskRepository
{
    /**
     * @var Task[]
     */
    private array $tasks = [];

    public function store(Task $task): void
    {
        $this->tasks[$task->getTaskId()->toString()] = clone $task;
    }

    public function getById(TaskId $taskId): Task
    {
        return $this->tasks[$taskId->toString()];
    }

    /**
     * @return Task[]
     */
    public function getUserTasksForGivenDate(User $user, Date $date): array
    {
        $list = [];
        foreach ($this->tasks as $task) {
            if ($task->getDate()->toString() === $date->toString() && $task->getUser()->toString() === $user->toString()) {
                $list[] = $task;
            }
        }
        return $list;
    }

    public function update(Task $task): void
    {
        if (isset($this->tasks[$task->getTaskId()->toString()])) {
            $this->tasks[$task->getTaskId()->toString()] = clone $task;
        }
    }

    public function getUserTasks(User $user): array
    {
        $list = [];
        foreach ($this->tasks as $task) {
            if ($task->getUser()->toString() === $user->toString()) {
                $list[] = $task;
            }
        }
        return $list;
    }
}