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
    public function __invoke(TaskUpdateCommand $command): Task
    {
        $task = $this->taskService->getById(TaskId::fromString($command->getTaskId()));
        $task->setUser($command->getUser() !== null ? User::fromString($command->getUser()) : $task->getUser());
        $task->setTitle($command->getTitle() !== null ? Title::fromString($command->getTitle()) : $task->getTitle());
        $task->setDate($command->getDate() !== null ? Date::create($command->getDate()) : $task->getDate());
        $task->setDescription($command->getDescription() !== null ? Description::fromString($command->getDescription()) : $task->getDescription());

        $scalarStatus = $command->getStatus();
        if (is_string($scalarStatus)) {
            $status = Status::fromLabel($scalarStatus);
        } elseif (is_int($scalarStatus)) {
            $status = Status::fromInt($scalarStatus);
        } else {
            $status = $task->getStatus();
        }
        $task->setStatus($status);
        $this->taskService->update($task);
        return $task;
    }
}