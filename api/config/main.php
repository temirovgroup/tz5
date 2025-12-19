<?php

declare(strict_types=1);

use yii\web\JsonParser;

$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

return [
  'id' => 'app-api',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerNamespace' => 'api\controllers',
  'modules' => [
    'v1' => [
      'basePath' => '@api/modules/v1',
      'class' => 'api\modules\Module',
    ],
  ],
  'components' => [
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => false,
      'enableSession' => false,
      'loginUrl' => null,
    ],
    'request' => [
      'baseUrl' => '/api',
      'class' => \yii\web\Request::class,
      'parsers' => [
        'application/json' => JsonParser::class,
      ],
      'enableCsrfValidation' => false,
      'enableCookieValidation' => false,
    ],
    'response' => [
      'class' => yii\web\Response::class,
      'format' => yii\web\Response::FORMAT_JSON,
      'charset' => 'UTF-8',
      'formatters' => [
        yii\web\Response::FORMAT_JSON => [
          'class' => yii\web\JsonResponseFormatter::class,
          'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
          'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        ],
      ],
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
          'logFile' => '@api/runtime/logs/app.log',
        ],
      ],
    ],
    'urlManager' => [
      'enablePrettyUrl' => true,
      'enableStrictParsing' => true,
      'showScriptName' => false,
      'rules' => [
        [
          'class' => 'yii\rest\UrlRule',
          'controller' => ['v1/task'],
          'prefix' => '',
          'pluralize' => true,
        ],
        'v1/documentation' => 'v1/documentation/index',
        'v1/documentation/<action:[\w-]+>' => 'v1/documentation/<action>',
        '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
      ],
    ],
  ],
  'params' => $params,
];
