<?php


namespace TaskManagement\Infrastructure\Repository;


use Doctrine\DBAL\Connection;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Exception\TaskNotFoundException;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\TaskRepository;
use TaskManagement\Domain\Task\Title;
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

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws TaskNotFoundException
     * @throws \Exception
     */
    public function getById(TaskId $taskId): Task
    {
        $result = $this->connection->fetchAllAssociative("select * from tasks where uuid = ? limit 1", [$taskId->toString()]);
        if (empty($result)) {
            throw new TaskNotFoundException(sprintf("Task with id %s not found", $taskId->toString()));
        }
        $result = $result[0];
        return Task::create(
            taskId: $taskId,
            user: User::fromString($result['user_uuid']),
            title: Title::fromString($result['title']),
            description: Description::fromString($result['description']),
            status: Status::fromInt(intval($result['status'])),
            date: Date::create(new \DateTimeImmutable($result['date']))
        );
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