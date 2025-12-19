<?php

declare(strict_types=1);

use yii\db\Expression;
use yii\db\Migration;

class m251217_191430_create_task_table extends Migration
{
  public const int STATUS_NEW = 10;
  public const int STATUS_IN_PROCESS = 20;
  public const int STATUS_COMPLETED = 30;
  
  public function safeUp()
  {
    $this->createTable('task', [
      'id' => $this->primaryKey(),
      'title' => $this->string()->notNull(),
      'description' => $this->text(),
      'status' => $this->tinyInteger(1)->notNull()->defaultValue(self::STATUS_NEW),
      'created_at' => $this->timestamp()->defaultValue(new Expression('NOW()'))->notNull(),
    ]);
  }
  
  public function safeDown()
  {
    $this->dropTable('task');
  }
}
