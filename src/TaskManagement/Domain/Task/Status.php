<?php


namespace TaskManagement\Domain\Task;


use TaskManagement\Domain\Task\Exception\InvalidTaskStatusException;

class Status
{

    const INCOMPLETE = 1;
    const COMPLETED  = 2;
    const DRAFT      = 3;

    private static array $availableStatuses = [self::INCOMPLETE, self::COMPLETED, self::DRAFT];

    private static array $statusRestrictions = [
        self::DRAFT => [self::COMPLETED, self::INCOMPLETE],
    ];

    private function __construct(private int $status)
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
                    implode(",", self::$availableStatuses)
                )
            );
        }

        return new self($status);
    }

    public static function getAvailableStatuses(): array
    {
        return self::$availableStatuses;
    }

    public function check(Status $status): void
    {
        if (isset(self::$statusRestrictions[$status->getValue()]) && in_array($this->status, self::$statusRestrictions[$status->getValue()])) {
            throw new InvalidTaskStatusException(
                sprintf(
                    "Status can't be changed from %d to %d.",
                    $this->status,
                    $status->getValue()
                )
            );
        }
    }

    public function getValue(): int
    {
        return $this->status;
    }
}