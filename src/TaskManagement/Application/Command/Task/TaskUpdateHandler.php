<?php


namespace TaskManagement\Application\Command\Task;


use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
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

    public function __invoke(TaskUpdateCommand $command)
    {
        $task = Task::create(
            taskId: TaskId::fromString($command->getTaskId()),
            user: User::fromString($command->getUser()),
            title: Title::fromString($command->getTitle()),
            description: Description::fromString($command->getDescription()),
            status: Status::fromInt($command->getStatus()),
            date: Date::create($command->getDate())
        );
        $this->taskService->update($task);
    }
}