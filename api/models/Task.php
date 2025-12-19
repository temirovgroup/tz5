<?php

declare(strict_types=1);

namespace api\models;

use api\enums\TaskStatusEnum;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $status
 * @property string $created_at
 */
class Task extends ActiveRecord
{
  public static function tableName(): string
  {
    return 'task';
  }
  
  public function rules(): array
  {
    $statuses = array_column(TaskStatusEnum::cases(), 'value');
    
    return [
      [['description'], 'default', 'value' => null],
      [['status'], 'default', 'value' => TaskStatusEnum::STATUS_NEW->value],
      [['title'], 'required'],
      [['description'], 'string'],
      [['status'], 'integer'],
      [['status'], 'in', 'range' => $statuses],
      [['created_at'], 'safe'],
      [['title'], 'string', 'max' => 255],
    ];
  }
  
  public function attributeLabels(): array
  {
    return [
      'id' => 'ID',
      'title' => 'Title',
      'description' => 'Description',
      'status' => 'Status',
      'created_at' => 'Created At',
    ];
  }
}
