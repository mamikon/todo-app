<?php


namespace TaskManagement\Application\Query;


use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskRepository;
use TaskManagement\Domain\Task\User;

class TaskQuery
{
    public function __construct(private TaskRepository $repository)
    {

    }

    /**
     * @return TaskDTO[]
     */
    public function getUserTasksForDate(string $user, \DateTimeImmutable $date): array
    {
        $taskList = $this->repository->getUserTasksForGivenDate(User::fromString($user), Date::create($date));
        return \array_map(function (Task $task) {
            return new TaskDTO(taskId: $task->getTaskId()->toString(),
                userId: $task->getUser()->toString(),
                title: $task->getTitle()->toString(),
                description: $task->getDescription()->toString(),
                status: $task->getStatus()->getValue(),
                date: $task->getDate()->toString()
            );

        }, $taskList);

    }
}