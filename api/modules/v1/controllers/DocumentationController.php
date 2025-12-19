<?php

declare(strict_types=1);

namespace api\modules\v1\controllers;

use light\swagger\SwaggerAction;
use light\swagger\SwaggerApiAction;
use Yii;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\Response;

final class DocumentationController extends Controller
{
  /*public function behaviors(): array
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'rules' => [
          [
            'allow' => true,
            'roles' => [RbacController::VIEW_APP_API_DOCS],
          ],
        ],
      ],
    ];
  }*/

  public function init(): void
  {
    parent::init();
    
    Yii::$app->response->format = Response::FORMAT_HTML;
    Yii::$app->user->enableSession = true;
  }

  public function actions(): array
  {
    return [
      'index' => [
        'class' => SwaggerAction::class,
        'restUrl' => Url::to(['request', 'json' => 'standart'], true),
      ],
      'request' => [
        'class' => SwaggerApiAction::class,
        'scanDir' => [
          Yii::getAlias('@api'),
//          Yii::getAlias('@api/modules/v1/controllers'),
        ],
      ],
    ];
  }
}
