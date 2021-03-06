<?php

namespace TaskManagement\Infrastructure\Repository;

use function array_map;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
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
    public const TABLE = 'tasks';

    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function store(Task $task): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'uuid'        => $task->getTaskId()->toString(),
                'user_uuid'   => $task->getUser()->toString(),
                'title'       => $task->getTitle()->toString(),
                'description' => $task->getDescription()->toString(),
                'date'        => $task->getDate()->toString(),
                'status'      => $task->getStatus()->getValue(),
            ]
        );
    }

    /**
     * @throws Exception
     * @throws TaskNotFoundException
     * @throws \Exception
     */
    public function getById(TaskId $taskId): Task
    {
        $result = $this->connection->fetchAllAssociative(
            'select uuid, title, description, status, date, user_uuid from tasks where uuid = ? limit 1',
            [$taskId->toString()]
        );
        if (empty($result)) {
            throw new TaskNotFoundException(sprintf('Task with id %s not found', $taskId->toString()));
        }
        $result = $result[0];

        return Task::create(
            taskId: $taskId,
            user: User::fromString($result['user_uuid']),
            title: Title::fromString($result['title']),
            description: Description::fromString($result['description']),
            status: Status::fromInt(intval($result['status'])),
            date: Date::create(new DateTimeImmutable($result['date']))
        );
    }

    /**
     * @return Task[]
     *
     * @throws Exception
     * @throws \Exception
     */
    public function getUserTasksForGivenDate(User $user, Date $date): array
    {
        $result = $this->connection->fetchAllAssociative(
            'select uuid, title, description, status, date, user_uuid from tasks where user_uuid = ? and date = ?',
            [$user->toString(), $date->toString()]
        );

        return array_map(
            function ($data) {
                return Task::create(
                    taskId: TaskId::fromString($data['uuid']),
                    user: User::fromString($data['user_uuid']),
                    title: Title::fromString($data['title']),
                    description: Description::fromString($data['description']),
                    status: Status::fromInt(intval($data['status'])),
                    date: Date::create(new DateTimeImmutable($data['date']))
                );
            },
            $result
        );
    }

    /**
     * @throws Exception
     */
    public function update(Task $task): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'user_uuid'   => $task->getUser()->toString(),
                'title'       => $task->getTitle()->toString(),
                'description' => $task->getDescription()->toString(),
                'status'      => $task->getStatus()->getValue(),
                'date'        => $task->getDate()->toString(),
            ],
            ['uuid' => $task->getTaskId()->toString()]
        );
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getUserTasks(User $user): array
    {
        $result = $this->connection->fetchAllAssociative(
            'select uuid, title, description, status, date, user_uuid from tasks where user_uuid = ?',
            [$user->toString()]
        );

        return array_map(
            function ($data) {
                return Task::create(
                    taskId: TaskId::fromString($data['uuid']),
                    user: User::fromString($data['user_uuid']),
                    title: Title::fromString($data['title']),
                    description: Description::fromString($data['description']),
                    status: Status::fromInt(intval($data['status'])),
                    date: Date::create(new DateTimeImmutable($data['date']))
                );
            },
            $result
        );
    }
}
