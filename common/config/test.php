<?php

$params = array_merge(
  require __DIR__ . '/../../common/config/test-params.php',
);

return [
  'id' => 'tz5_test',
  'basePath' => dirname(__DIR__),
  'components' => [
   
    'request' => [
      'cookieValidationKey' => 'test',
    ],

    'i18n' => [
      'translations' => [
        'calc*' => [
          'class' => \yii\i18n\PhpMessageSource::class,
          'basePath' => '@common/messages',
          'fileMap' => [
            'calc' => 'calc.php',
          ],
        ],
      ],
    ],
  ],
  'params' => $params,
];
