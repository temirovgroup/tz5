<?php

declare(strict_types=1);

namespace api\schemas;

use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  schema: 'ApiSuccessResponse',
  title: 'API Success Response',
  description: 'Формат успешного ответа API с поддержкой различных типов данных. Может содержать произвольные полезные данные в поле data',
  allOf: [
    new OA\Schema(ref: '#/components/schemas/ApiResponseDto'),
    new OA\Schema(
      properties: [
        new OA\Property(
          property: 'data',
          description: 'Полезные данные. Может быть null, объект, массив, строка и т.д.',
          nullable: true,
        ),
      ],
    ),
  ],
)]
final class ApiSuccessResponseDto extends ApiResponseDto
{
  public function __construct(
    protected string $message,
    protected mixed $data = null,
    protected ?string $timestamp = null,
  ) {
    parent::__construct(success: true, message: $message, timestamp: $timestamp);
  }

  public function jsonSerialize(): array
  {
    $data = parent::jsonSerialize();
    $data['data'] = $this->normalizeData($this->data);

    return $data;
  }

  private function normalizeData(mixed $data): mixed
  {
    if ($data instanceof JsonSerializable) {
      return $data->jsonSerialize();
    }

    if (is_scalar($data) || is_array($data) || $data === null) {
      return $data;
    }

    if (is_object($data)) {
      return (array) $data;
    }

    return null;
  }

  public function getData(): mixed
  {
    return $this->data;
  }
}
