<?php


namespace TaskManagement\Domain\Task;


use TaskManagement\Domain\Task\Exception\InvalidTaskStatusException;

class Status
{

    const INCOMPLETE = 1;
    const COMPLETED  = 2;
    const DRAFT      = 3;

    private static array $availableStatuses = [self::INCOMPLETE, self::COMPLETED, self::DRAFT];

    public function __construct(private int $status)
    {

    }

    /**
     * @throws InvalidTaskStatusException
     */
    public static function fromInt(int $status): self
    {
        if (!in_array($status, self::$availableStatuses)) {
            throw new InvalidTaskStatusException(
                sprintf(
                    "Provided status(%d) is invalid. Available statuses are %s",
                    $status,
                    implode(",", self::$availableStatuses))
            );
        }

        return new self($status);
    }

    public static function getAvailableStatuses(): array
    {
        return self::$availableStatuses;
    }

    public function change(int $status): self
    {
        return self::fromInt($status);
    }

    public function getValue(): int
    {
        return $this->status;
    }
}