<?php

namespace TaskManagement\Application\Query;

use function array_map;
use DateTimeImmutable;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskRepository;
use TaskManagement\Domain\Task\User;

class TaskQuery
{
    public function __construct(private TaskRepository $repository)
    {
    }

    private function taskDtoConverter(Task $task): TaskDTO
    {
        return new TaskDTO(
            taskId: $task->getTaskId()->toString(),
            userId: $task->getUser()->toString(),
            title: $task->getTitle()->toString(),
            description: $task->getDescription()->toString(),
            status: $task->getStatus()->getValue(),
            date: $task->getDate()->toString()
        );
    }

    /**
     * @return TaskDTO[]
     */
    public function getUserTasksForDate(string $user, DateTimeImmutable $date): array
    {
        $taskList = $this->repository->getUserTasksForGivenDate(User::fromString($user), Date::create($date));

        return array_map(
            function (Task $task) {
                return $this->taskDtoConverter($task);
            },
            $taskList
        );
    }

    /**
     * @return TaskDTO[]
     */
    public function getUserTasks(string $user): array
    {
        $taskList = $this->repository->getUserTasks(User::fromString($user));

        return array_map(
            function (Task $task) {
                return $this->taskDtoConverter($task);
            },
            $taskList
        );
    }

    public function getTaskById(string $uuid): TaskDTO
    {
        $task = $this->repository->getById(TaskId::fromString($uuid));

        return $this->taskDtoConverter($task);
    }
}
