<?php


use TaskManagement\Application\Query\TaskDTO;
use TaskManagement\Domain\Task\Date;
use TaskManagement\Domain\Task\Description;
use TaskManagement\Domain\Task\Status;
use TaskManagement\Domain\Task\Task;
use TaskManagement\Domain\Task\TaskId;
use TaskManagement\Domain\Task\Title;
use TaskManagement\Domain\Task\User;

class TaskQueryTest extends \PHPUnit\Framework\TestCase
{
    public function test_query_returns_right_tasks_for_user_in_given_period()
    {
        require_once(__DIR__ . '/../../Domain/Task/stubs/InMemoryRepository.php');
        $repository = new \TaskManagement\Domain\Task\stubs\InMemoryRepository();
        $user1      = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $user2      = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $date1      = "2000-01-01 10:10:10";
        $date2      = "2001-01-01 10:10:10";
        $taskList[] = $this->generateTask($user1, "title", "description", Status::INCOMPLETE, $date1);
        $taskList[] = $this->generateTask($user1, "title2", "description2", Status::COMPLETED, $date1);
        $taskList[] = $this->generateTask($user2, "title", "description", Status::INCOMPLETE, $date1);
        $taskList[] = $this->generateTask($user1, "title", "description", Status::INCOMPLETE, $date2);

        foreach ($taskList as $task) {
            $repository->store($task);
        }
        $taskQuery = new \TaskManagement\Application\Query\TaskQuery($repository);
        $tasks     = $taskQuery->getUserTasksForDate($user1, new DateTimeImmutable($date1));

        $this->assertIsArray($tasks);
        foreach ($tasks as $task) {
            $this->assertInstanceOf(TaskDTO::class, $task);
            $this->assertSame($user1, $task->getUserId());
            $this->assertSame("2000-01-01", $task->getDate());
        }


    }

    private function generateTask(string $userId, string $title, string $description, int $status, string $date): Task
    {
        $taskId = TaskId::generate();
        return Task::create(
            taskId: $taskId,
            user: User::fromString($userId),
            title: Title::fromString($title),
            description: Description::fromString($description),
            status: Status::fromInt($status),
            date: Date::create(new DateTimeImmutable($date))
        );
    }
}