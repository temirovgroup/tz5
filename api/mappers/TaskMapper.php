<?php

declare(strict_types=1);

namespace api\mappers;

use api\models\Task;
use api\schemas\task\TaskDto;

final readonly class TaskMapper
{
  public static function toDto(Task|array $task): TaskDto
  {
    $attributes = is_array($task) ? $task : $task->attributes;
   
    return new TaskDto(
      id: (int)$attributes['id'],
      title: $attributes['title'],
      description: $attributes['description'],
      status: (int)$attributes['status'],
      created_at: $attributes['created_at'],
    );
  }
  
  public static function toDtoCollection(array $tasks): array
  {
    return array_map(
      fn($task) => self::toDto($task),
      $tasks
    );
  }
}
