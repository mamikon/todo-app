<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Task;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Security\Core\Security;
use TaskManagement\Application\Command\Task\TaskCreateCommand;

class TaskCreatePersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private Security $security, private MessageBusInterface $messageBus)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Task && $context['collection_operation_name'] === 'post';
    }

    /**
     * @param Task $data
     * @param array $context
     * @return Task
     * @throws \Exception
     */
    public function persist($data, array $context = []): Task
    {

        $task   = new TaskCreateCommand(
            user: $this->security->getUser()->getUuid(),
            title: $data->getTitle(),
            date: new \DateTimeImmutable($data->getDate()),
            description: $data->getDescription(),
            status: $data->getStatus()
        );
        $result = $this->messageBus->dispatch($task);
        /** @var \TaskManagement\Domain\Task\Task $handledTask */
        $handledTask = $result->last(HandledStamp::class)->getResult();
        $data->setUuid($handledTask->getTaskId()->toString());
        return $data;
    }

    public function remove($data, array $context = [])
    {
        return $data;
    }
}