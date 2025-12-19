<?php

declare(strict_types=1);

namespace api\traits;

use api\enums\ResponseCode;
use api\enums\ResponseCodeMessage;
use api\modules\traits\Yii;
use api\modules\v1\controllers\BaseApiController;
use api\schemas\ApiErrorResponseDto;
use api\schemas\ApiSuccessResponseDto;
use api\schemas\ValidationErrorItem;
use yii\web\Response;

/**
 * Трейт для стандартизированных ответов API.
 *
 * Предоставляет методы для формирования успешных и ошибочных ответов
 * в едином формате для всего API.
 *
 * @used-by BaseApiController
 */
trait ResponseTrait
{
  /**
   * Формирует успешный ответ API.
   *
   * @param mixed $data Данные для возврата
   * @param string $message Сообщение об успехе
   * @return Response Форматированный успешный ответ
   */
  protected function asSuccess(mixed $data = null, string $message = ResponseCodeMessage::SUCCESS->value): Response
  {
    $dto = new ApiSuccessResponseDto(
      message: $message,
      data: $data,
      timestamp: gmdate('c'),
    );
    
    return $this->asJson($dto);
  }
  
  /**
   * Формирует ответ с ошибкой API.
   *
   * @param string $message Сообщение об ошибке
   * @param array $errors Массив ошибок валидации
   * @param ResponseCode $code HTTP-статус код ошибки
   * @return Response Форматированный ответ с ошибкой
   */
  protected function asError(
    string $message,
    array $errors = [],
    ResponseCode $code = ResponseCode::BAD_REQUEST,
  ): Response {
    $formattedErrors = $errors === [] ? null : $this->formatValidationErrors($errors);
    
    $dto = new ApiErrorResponseDto(
      message: $message,
      code: $code->name,
      status: $code->value,
      errors: $formattedErrors,
      timestamp: gmdate('c'),
    );
    
    $this->response->setStatusCode($dto->getStatus());
    
    return $this->asJson($dto);
  }
  
  /**
   * Формирует ответ с ошибкой валидации.
   *
   * @param string $message Сообщение об ошибке
   * @param array $errors Массив ошибок валидации
   * @return Response Форматированный ответ с ошибкой валидации
   */
  protected function asValidationError(string $message, array $errors): Response
  {
    return $this->asError(
      message: $message,
      errors: $errors,
      code: ResponseCode::VALIDATION_ERROR,
    );
  }
  
  /**
   * Форматирует ошибки валидации в единый формат.
   *
   * @param array $errors Ошибки валидации в формате Yii2
   * @return array Отформатированные ошибки
   */
  protected function formatValidationErrors(array $errors): array
  {
    $formattedErrors = [];
    
    foreach ($errors as $attribute => $messages) {
      foreach ((array) $messages as $message) {
        $formattedErrors[] = new ValidationErrorItem(
          attribute: (string) $attribute,
          message: (string) $message,
        );
      }
    }
    
    return $formattedErrors;
  }
  
  /**
   * Отправляет ошибку и завершает выполнение приложения.
   *
   * Используется для принудительного завершения с ошибкой.
   *
   * @param Response $response Ответ с ошибкой
   */
  protected function sendErrorAndEnd(Response $response): never
  {
    $this->response->setStatusCode($response->statusCode);
    $this->response->format = Response::FORMAT_JSON;
    $this->response->data = json_decode($response->content, true, 512, JSON_THROW_ON_ERROR);
    Yii::$app->end();
    exit;
  }
}
