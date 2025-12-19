<?php

declare(strict_types=1);

namespace api\traits;

use api\modules\Module;
use api\modules\v1\controllers\BaseApiController;
use yii\web\Response;

/**
 * Трейт для валидации идентификаторов.
 *
 * Предоставляет методы для проверки валидности ID в запросах.
 *
 * @used-by BaseApiController
 */
trait IdValidatorTrait
{
  /**
   * Валидирует идентификатор.
   *
   * Проверяет, что ID является положительным числом.
   *
   * @param int $id Идентификатор для валидации
   * @return Response|null Ответ с ошибкой валидации или null если валидно
   */
  protected function validateId(int $id): ?Response
  {
    if ($id <= 0) {
      return $this->asValidationError(
        message: 'ID должен быть положительным числом.',
        errors: ['id' => 'ID должен быть положительным числом.'],
      );
    }

    return null;
  }
}
