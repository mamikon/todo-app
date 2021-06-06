<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Task;
use DateTimeImmutable;
use Generator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use TaskManagement\Application\Query\TaskQuery;

class TaskCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private RequestStack $request, private Security $security, private TaskQuery $query)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): Generator
    {
        $date = $this->request->getCurrentRequest()->get('date', false);
        if ($date && $date = new DateTimeImmutable($date)) {
            $list = $this->query->getUserTasksForDate($this->security->getUser()->getUuid(), $date);
        } else {
            $list = $this->query->getUserTasks($this->security->getUser()->getUuid());
        }
        foreach ($list as $taskDTO) {
            yield TaskDtoToResourceAdapter::convert($taskDTO);
        }
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Task::class === $resourceClass && 'get' === $operationName;
    }
}
