<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Task;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;
use TaskManagement\Application\Command\Task\TaskUpdateCommand;
use TaskManagement\Domain\Task\Exception\InvalidTaskStatusException;

class TaskUpdatePersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private Security $security, private MessageBusInterface $messageBus)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Task && 'put' === ($context['item_operation_name'] ?? false);
    }

    /**
     * @param Task $data
     * @param array $context
     * @return object|void
     * @throws \Exception
     */
    public function persist($data, array $context = [])
    {
        $task = new TaskUpdateCommand(
            taskId: $data->getUuid(),
            title: $data->getTitle(),
            date: new \DateTimeImmutable($data->getDate()),
            description: $data->getDescription(),
            status: $data->getStatus()
        );
        try {
            $this->messageBus->dispatch($task);
        } catch (HandlerFailedException  $exception) {
            throw $exception->getPrevious();
        }
        return $data;
    }

    public function remove($data, array $context = [])
    {
    }
}