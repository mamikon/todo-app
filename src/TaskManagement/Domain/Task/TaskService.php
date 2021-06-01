<?php


namespace TaskManagement\Domain\Task;


class TaskService
{


    public function __construct(private TaskRepository $repository)
    {
    }

    public function store(Task $task): void
    {
        $this->repository->store($task);
    }

    public function getById(TaskId $taskId): Task
    {
        return $this->repository->getById($taskId);
    }

    /**
     * @return Task[]
     */
    public function getUserTasksForGivenDate(User $user, Date $date): array
    {
        return $this->repository->getUserTasksForGivenDate($user, $date);
    }
}