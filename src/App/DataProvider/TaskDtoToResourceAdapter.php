<?php

namespace App\DataProvider;

use App\Entity\Task;
use TaskManagement\Application\Query\TaskDTO;
use TaskManagement\Domain\Task\Status;

class TaskDtoToResourceAdapter
{
    private function __construct()
    {
    }

    public static function convert(TaskDTO $taskDTO): Task
    {
        $task = new Task();
        $task->setUuid($taskDTO->getTaskId());
        $task->setUserUuid($taskDTO->getUserId());
        $task->setTitle($taskDTO->getTitle());
        $task->setDescription($taskDTO->getDescription());
        $task->setStatus(Status::getLabel($taskDTO->getStatus()));
        $task->setDate($taskDTO->getDate());

        return $task;
    }
}
