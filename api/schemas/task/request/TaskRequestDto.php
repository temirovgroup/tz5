<?php

declare(strict_types=1);

namespace api\schemas\task\request;

use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  schema: 'TaskRequest',
  title: 'Task Request',
  description: 'Запрос для получения задачи',
)]
final readonly class TaskRequestDto implements JsonSerializable
{
  public function __construct(
    #[OA\Property(
      description: 'ID задачи',
      type: 'integer',
      example: 1,
    )]
    private int $id,
  )
  {}
  
  public function getId(): int
  {
    return $this->id;
  }
  
  public function jsonSerialize(): mixed
  {
    return [
      'id' => $this->id,
    ];
  }
}
