<?php

declare(strict_types=1);

Yii::setAlias('@api', dirname(__DIR__));

// Создаем директорию assets при загрузке приложения
$assetsPath = Yii::getAlias('@api/web/assets');

if (!is_dir($assetsPath)) {
  if (!mkdir($assetsPath, 0775, true) && !is_dir($assetsPath)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $assetsPath));
  }
}
