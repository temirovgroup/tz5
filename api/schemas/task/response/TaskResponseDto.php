<?php

declare(strict_types=1);

namespace api\schemas\task\response;

use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  schema: 'TasksResponse',
  title: 'Tasks Response',
  description: 'Ответ со списком задач',
)]
final readonly class TaskResponseDto implements JsonSerializable
{
  public function __construct(
    #[OA\Property(
      description: 'Массив задач',
      type: 'array',
      items: new OA\Items(ref: '#/components/schemas/Task'),
    )]
    private array $tasks,
  )
  {}
  
  public function getTasks()
  {
    return $this->tasks;
  }
  
  public function jsonSerialize(): mixed
  {
    return [
      'tasks' => $this->tasks,
    ];
  }
}
