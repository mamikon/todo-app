<?php


namespace TaskManagement\Application\Command\Task;


use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Exception\TaskNotFoundException;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskService;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class TaskUpdateHandler
{
    public function __construct(private TaskService $taskService)
    {

    }

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(TaskUpdateCommand $command)
    {
        $task        = $this->taskService->getById(TaskId::fromString($command->getTaskId()));
        $status      = $command->getStatus() !== null ? Status::fromInt($command->getStatus()) : $task->getStatus();
        $user        = $command->getUser() !== null ? User::fromString($command->getUser()) : $task->getUser();
        $title       = $command->getTitle() !== null ? Title::fromString($command->getTitle()) : $task->getTitle();
        $date        = $command->getDate() !== null ? Date::create($command->getDate()) : $task->getDate();
        $description = $command->getDescription() !== null ? Description::fromString($command->getDescription()) : $task->getDescription();

        $task = Task::create(
            taskId: $task->getTaskId(),
            user: $user,
            title: $title,
            description: $description,
            status: $status,
            date: $date
        );
        $this->taskService->update($task);
    }
}