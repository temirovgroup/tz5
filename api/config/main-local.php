<?php

declare(strict_types=1);

$config = [];

if (YII_ENV_DEV && !YII_ENV_TEST) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
    'class' => \yii\debug\Module::class,
    'allowedIPs' => ['*'],
  ];
  
  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => ['*'],
  ];
  
}

return $config;
