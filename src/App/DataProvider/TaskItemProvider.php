<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Task;
use TaskManagement\Application\Query\TaskQuery;
use TaskManagement\Domain\Task\Exception\TaskNotFoundException;
use TaskManagement\Domain\Task\Status;

class TaskItemProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private TaskQuery $query)
    {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Task
    {
        try {
            $taskDto = $this->query->getTaskById($id);
            $task    = new Task();
            $task->setUuid($id);
            $task->setUserUuid($taskDto->getUserId());
            $task->setTitle($taskDto->getTitle());
            $task->setDescription($taskDto->getDescription());
            $task->setStatus(Status::getLabel($taskDto->getStatus()));
            $task->setDate($taskDto->getDate());
            return $task;
        } catch (TaskNotFoundException $e) {
            return null;
        }
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Task::class;
    }
}