<?php

declare(strict_types=1);

$config = [
  'components' => [
    'cache' => [
      'class' => 'yii\caching\MemCache', // use DummyCache for no real caching
      'keyPrefix' => 'exampleapp',
      'useMemcached' => true,
      'servers' => [
        'master' => [
          'host' => 'memcached',
          'port' => 11211,
          'weight' => 60,
        ],
      ],
    ],
  ],
];

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
