<?php

declare(strict_types=1);

namespace api\schemas;

use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  title: 'Base API Response',
  description: 'Общий формат ответа. Все ответы наследуют эту структуру.',
  required: ['success', 'message'],
  properties: [
    new OA\Property(property: 'success', type: 'boolean', example: true),
    new OA\Property(property: 'message', type: 'string', example: 'Операция выполнена'),
    new OA\Property(property: 'timestamp', type: 'string', format: 'date-time'),
    new OA\Property(property: 'request_id', type: 'string', example: 'req-abc123'),
  ],
  type: 'object',
)]
abstract class ApiResponseDto implements JsonSerializable
{
  public function __construct(
    protected bool $success,
    protected string $message,
    protected ?string $timestamp = null,
  ) {}

  public function jsonSerialize(): array
  {
    $result = [
      'success' => $this->success,
      'message' => $this->message,
    ];

    $result['timestamp'] = $this->timestamp ?? gmdate('c');

    return $result;
  }

  public function isSuccess(): bool
  {
    return $this->success;
  }

  public function getMessage(): string
  {
    return $this->message;
  }

  public function getTimestamp(): ?string
  {
    return $this->timestamp;
  }
}
