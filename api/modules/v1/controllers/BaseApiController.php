<?php

declare(strict_types=1);

namespace api\modules\v1\controllers;

use api\enums\ResponseCode;
use api\traits\IdValidatorTrait;
use api\traits\ResponseTrait;
use Override;
use Throwable;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\User;

class BaseApiController extends Controller
{
  use ResponseTrait;
  use IdValidatorTrait;
  
  protected ?User $user = null;
  
  /**
   * Инициализация контроллера.
   * Генерирует уникальный идентификатор запроса.
   */
  public function init(): void
  {
    parent::init();
    $this->user = Yii::$app->user;
  }
  
  /**
   * Обрабатывает исключения и возвращает соответствующий HTTP-ответ.
   *
   * @param Throwable $e Пойманное исключение
   * @param string $context Контекст операции для логирования
   * @return Response Форматированный HTTP-ответ с ошибкой
   */
  protected function handleException(Throwable $e, string $context): Response
  {
    // Логирование исключения с контекстом
    
    return $this->asError(
      message: ResponseCode::SERVER_ERROR->friendly(),
      code: ResponseCode::SERVER_ERROR,
    );
  }
  
  /**
   * Отправляет данные в формате JSON.
   *
   * Переопределяет родительский метод для добавления заголовка X-Request-ID.
   *
   * @param mixed $data Данные для отправки
   * @return Response Ответ с данными в формате JSON
   */
  #[Override]
  public function asJson($data): Response
  {
    $this->response->format = Response::FORMAT_JSON;
    
    return parent::asJson($data);
  }
}
