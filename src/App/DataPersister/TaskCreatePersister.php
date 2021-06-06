<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Task;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Security\Core\Security;
use TaskManagement\Application\Command\Task\TaskCreateCommand;
use Throwable;

class TaskCreatePersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private Security $security, private MessageBusInterface $messageBus)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Task && 'post' === ($context['collection_operation_name'] ?? false);
    }

    /**
     * @param Task $data
     *
     * @throws Exception|Throwable
     */
    public function persist($data, array $context = []): Task
    {
        $task = new TaskCreateCommand(
            user: $this->security->getUser()->getUuid(),
            title: $data->getTitle(),
            date: new DateTimeImmutable($data->getDate()),
            description: $data->getDescription(),
            status: $data->getStatus()
        );
        try {
            $result = $this->messageBus->dispatch($task);
        } catch (HandlerFailedException  $exception) {
            throw $exception->getPrevious();
        }

        /** @var \TaskManagement\Domain\Task\Task $handledTask */
        $handledTask = $result->last(HandledStamp::class)->getResult();
        $data->setUuid($handledTask->getTaskId()->toString());
        $data->setUserUuid($this->security->getUser()->getUuid());

        return $data;
    }

    public function remove($data, array $context = [])
    {
    }
}
