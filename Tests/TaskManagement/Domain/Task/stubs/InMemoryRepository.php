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
        $this->tasks[$task->getTaskId()->toString()] = $task;
    }

    public function getById(TaskId $taskId): Task
    {
        return $this->tasks[$taskId->toString()];
    }

    public function getUsersTaskForGivenDate(User $user, Date $date): array
    {
        $list = [];
        foreach ($this->tasks as $task) {
            if ($task->getDate()->toString() === $date->toString() && $task->getUser()->toString() === $user->toString()) {
                $list[] = $task;
            }
        }
        return $list;
    }
}