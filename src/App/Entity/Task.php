<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    collectionOperations: [
    'post' => [
        'method'            => 'post',
        'validation_groups' => ['Default', 'postValidation']
    ],
    'get'  => [
        'method'          => 'get',
        'openapi_context' => [
            "parameters" => [
                'date' => [
                    'name'        => 'date',
                    'in'          => 'query',
                    'description' => 'Fetch tasks for given date. Possible values can be found [in php official documentation](https://www.php.net/manual/en/datetime.formats.php)',
                    'required'    => false,
                    'schema'      => [
                        'type'    => 'string',
                        'example' => 'today'
                    ]
                ]
            ]
        ]
    ]],
    itemOperations: [
    'get' => [
        "security" => "is_granted('TASK_VIEW', object)"
    ],
    'put' => [
        "security" => "is_granted('TASK_EDIT', object)"
    ]],
    attributes: ['pagination_enabled' => false],
)]
class Task
{
    #[ApiProperty(
        readable: true,
        writable: false,
        identifier: true
    )]
    #[Assert\Uuid]
    private string $uuid;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\NotNull(groups: ["postValidation"])]
    private ?string $title = null;

    private ?string $description = null;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\NotNull(groups: ["postValidation"])]
    #[Assert\Choice(
        callback: 'TaskManagement\Domain\Task\Status::getStatusLabels',
        message: "Provided {{ value }} status is invalid. Valid Statuses labels are {{ choices }}."
    )]
    #[ApiProperty(
        default: 'draft',
    )]
    private ?string $status = null;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\NotNull(groups: ["postValidation"])]
    #[Assert\Date]
    #[ApiProperty(
        default: "2000-01-20"
    )]
    private ?string $date = null;
    #[Ignore]
    private string $userUuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    public function getDescription(): string
    {
        return $this->description;
    }


    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    public function getDate(): string
    {
        return $this->date;
    }


    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }


    public function setUserUuid(string $userId): void
    {
        $this->userUuid = $userId;
    }

}