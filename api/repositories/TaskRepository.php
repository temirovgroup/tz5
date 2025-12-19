<?php

declare(strict_types=1);

namespace api\repositories;

use api\models\Task;

class TaskRepository
{
  public const string CACHE_KEY = 'tasks_list';
  public const int CACHE_DURATION = 300;
  
  public function findAll(): array
  {
    return Task::find()->asArray()->all();
  }
  
  public function findById(int $id): ?Task
  {
    return Task::findOne($id);
  }
  
  public function create(array $data): Task
  {
    $task = new Task();
    $task->load($data, '');
    
    if ($task->save()) {
      $task->refresh();
    }
    
    return $task;
  }
  
  public function update(int $id, array $data): ?Task
  {
    $task = $this->findById($id);
    
    if ($task === null) {
      return null;
    }
    
    $task->load($data, '');
    $task->save();
    
    return $task;
  }
  
  public function delete(int $id): bool
  {
    $task = $this->findById($id);
    
    return (bool) ($task?->delete() ?? false);
  }
}
