<?php


namespace TaskManagement\Domain\Task;


use TaskManagement\Domain\Task\Exception\InvalidTaskStatusException;

class Status
{

    const INCOMPLETE = 1;
    const COMPLETED  = 2;
    const DRAFT      = 3;

    private static array $availableStatuses = [self::INCOMPLETE, self::COMPLETED, self::DRAFT];
    private static array $statusLabels = [
        self::INCOMPLETE => 'incomplete',
        self::COMPLETED  => 'completed',
        self::DRAFT      => 'draft',
    ];

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

    public static function getLabel(int $statusIntVal): string
    {
        return self::$statusLabels[$statusIntVal] ?? throw new InvalidTaskStatusException(sprintf("Invalid status value provided(%s).", $statusIntVal));
    }

    private static function getIntValFromLabel(string $label): int
    {
        $list = \array_flip(self::$statusLabels);
        return $list[$label] ??
            throw  new InvalidTaskStatusException(
                sprintf("Invalid status label provided(%s). Valid Labels Are %s",
                    $label,
                    implode(', ', self::$statusLabels)
                )
            );
    }

    public static function fromLabel(string $label): self
    {
        return new self(self::getIntValFromLabel($label));
    }

    public static function getStatusLabels(): array
    {
        return self::$statusLabels;
    }

    public function check(Status $status): void
    {
        if (isset(self::$statusRestrictions[$status->getValue()]) && in_array($this->status, self::$statusRestrictions[$status->getValue()])) {
            throw new InvalidTaskStatusException(
                sprintf(
                    "Status can't be changed from %s to %s.",
                    self::getLabel($this->status),
                    self::getLabel($status->getValue())
                )
            );
        }
    }

    public function getValue(): int
    {
        return $this->status;
    }
}