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

class TaskCreateHandler
{
    public function __construct(private TaskService $taskService)
    {

    }

    public function __invoke(TaskCreateCommand $taskCreateCommand): Task
    {
        $task = Task::create(
            taskId: TaskId::generate(),
            user: User::fromString($taskCreateCommand->getUser()),
            title: Title::fromString($taskCreateCommand->getTitle()),
            description: Description::fromString($taskCreateCommand->getDescription()),
            status: Status::fromInt($taskCreateCommand->getStatus()),
            date: Date::create($taskCreateCommand->getDate()));
        $this->taskService->store($task);
        return $task;
    }
}