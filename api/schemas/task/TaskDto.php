<?php

declare(strict_types=1);

namespace api\schemas\task;

use api\enums\TaskStatusEnum;
use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  schema: 'Task',
  title: 'Task',
  description: 'Задача',
)]
final readonly class TaskDto implements JsonSerializable
{
  public function __construct(
    #[OA\Property(
      description: 'Уникальный идентификатор задачи',
      type: 'integer',
      example: 1,
    )]
    public int $id,
    #[OA\Property(
      description: 'Название задачи',
      type: 'string',
      example: 'Сделать домашнее задание',
    )]
    public string $title,
    #[OA\Property(
      description: 'Описание задачи',
      type: 'string',
      nullable: true,
      example: 'Необходимо выполнить все упражнения из учебника',
    )]
    public ?string $description,
    #[OA\Property(
      description: 'Статус задачи (10 - новая, 20 - в процессе, 30 - выполнена)',
      enum: TaskStatusEnum::class,
      example: TaskStatusEnum::STATUS_NEW->value,
    )]
    public int $status,
    #[OA\Property(
      description: 'Дата и время создания задачи',
      type: 'string',
      format: 'date-time',
      example: '2025-12-19 13:43:04',
    )]
    public string $created_at,
  )
  {}
  
  public function getId(): int
  {
    return $this->id;
  }
  
  public function getTitle(): string
  {
    return $this->title;
  }
  
  public function getDescription(): ?string
  {
    return $this->description;
  }
  
  public function getStatus(): int
  {
    return $this->status;
  }
  
  public function getCreatedAt(): string
  {
    return $this->created_at;
  }
  
  public function jsonSerialize(): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->description,
      'status' => $this->status,
      'created_at' => $this->created_at,
    ];
  }
}
