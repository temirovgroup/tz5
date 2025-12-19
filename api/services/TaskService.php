<?php

declare(strict_types=1);

namespace api\services;

use api\mappers\TaskMapper;
use api\models\Task;
use api\repositories\TaskRepository;
use api\schemas\task\response\TaskResponseDto;
use api\schemas\task\TaskDto;
use Yii;

class TaskService
{
  public function __construct(
    private readonly TaskRepository $repository
  ) {}
  
  public function getAllTasks(): TaskResponseDto
  {
    $tasks = Yii::$app->cache->getOrSet(
      TaskRepository::CACHE_KEY,
      fn() => $this->repository->findAll(),
      TaskRepository::CACHE_DURATION
    );
    
    $taskDtos = TaskMapper::toDtoCollection($tasks);
    
    return new TaskResponseDto($taskDtos);
  }
  
  public function getTaskById(int $id): ?TaskDto
  {
    $task = $this->repository->findById($id);
    return $task ? TaskMapper::toDto($task) : null;
  }
  
  public function createTask(array $data): TaskDto|Task
  {
    $task = $this->repository->create($data);
    
    if ($task->hasErrors()) {
      return $task;
    }
    
    $this->clearCache();
    return TaskMapper::toDto($task);
  }
  
  public function updateTask(int $id, array $data): TaskDto|Task|null
  {
    $task = $this->repository->update($id, $data);
    
    if ($task === null) {
      return null;
    }
    
    if ($task->hasErrors()) {
      return $task;
    }
    
    $this->clearCache();
    return TaskMapper::toDto($task);
  }
  
  public function deleteTask(int $id): bool
  {
    $deleted = $this->repository->delete($id);
    
    if ($deleted) {
      $this->clearCache();
    }
    
    return $deleted;
  }
  
  private function clearCache(): void
  {
    Yii::$app->cache->delete(TaskRepository::CACHE_KEY);
  }
}
