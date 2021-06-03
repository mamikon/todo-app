<?php


namespace TaskManagement\Infrastructure\Repository;


use Doctrine\DBAL\Connection;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskRepository;
use TaskManagement\Domain\Task\User;

class DbalTaskRepository implements TaskRepository
{
    const TABLE = 'tasks';

    public function __construct(private Connection $connection)
    {

    }

    public function store(Task $task): void
    {
        $this->connection->insert(self::TABLE, [
            'uuid'        => $task->getTaskId()->toString(),
            'user_uuid'   => $task->getUser()->toString(),
            'title'       => $task->getTitle()->toString(),
            'description' => $task->getDescription()->toString(),
            'date'        => $task->getDate()->toString(),
            'status'      => $task->getStatus()->getValue()
        ]);
    }

    public function getById(TaskId $taskId): Task
    {
        // TODO: Implement getById() method.
    }

    /**
     * @return Task[]
     */
    public function getUserTasksForGivenDate(User $user, Date $date): array
    {
        // TODO: Implement getUserTasksForGivenDate() method.
    }

    public function update(Task $task): void
    {
        // TODO: Implement update() method.
    }
}