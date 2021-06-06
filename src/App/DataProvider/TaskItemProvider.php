<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Task;
use TaskManagement\Application\Query\TaskQuery;
use TaskManagement\Domain\Task\Exception\TaskNotFoundException;

class TaskItemProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private TaskQuery $query)
    {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Task
    {
        try {
            $taskDto = $this->query->getTaskById($id);

            return TaskDtoToResourceAdapter::convert($taskDto);
        } catch (TaskNotFoundException $e) {
            return null;
        }
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Task::class === $resourceClass;
    }
}
