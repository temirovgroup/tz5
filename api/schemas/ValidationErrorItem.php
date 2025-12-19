<?php

declare(strict_types=1);

namespace api\schemas;

use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  schema: 'ValidationErrorItem',
  title: 'Validation Error Item',
  description: 'Детализированная информация об ошибке валидации отдельного поля запроса. Содержит имя поля и сообщение об ошибке',
  properties: [
    new OA\Property(property: 'attribute', type: 'string', example: 'id'),
    new OA\Property(property: 'message', type: 'string', example: 'ID должен быть целым числом'),
  ],
  type: 'object',
)]
final readonly class ValidationErrorItem implements JsonSerializable
{
  public function __construct(
    private string $attribute,
    private string $message,
  ) {}

  public function getAttribute(): string
  {
    return $this->attribute;
  }

  public function getMessage(): string
  {
    return $this->message;
  }

  public function jsonSerialize(): array
  {
    return [
      'attribute' => $this->attribute,
      'message' => $this->message,
    ];
  }
}
