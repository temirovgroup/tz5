<?php

declare(strict_types=1);

namespace api\schemas;

use api\enums\ResponseCode;
use api\enums\ResponseCodeMessage;
use JsonSerializable;
use OpenApi\Attributes as OA;

#[OA\Schema(
  schema: 'ApiErrorResponse',
  title: 'API Error Response',
  description: 'Стандартизированный формат ответа для ошибок API. Содержит машинно-читаемый код ошибки, HTTP статус и детализацию ошибок',
  allOf: [
    new OA\Schema(ref: '#/components/schemas/ApiResponseDto'),
    new OA\Schema(
      required: ['code', 'status'],
      properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'Операция завершилась ошибкой'),
        new OA\Property(
          property: 'code',
          description: 'Машинно-читаемый код ошибки',
          type: 'string',
          example: ResponseCodeMessage::SERVER_ERROR->value,
        ),
        new OA\Property(
          property: 'status',
          description: 'HTTP статус код',
          type: 'integer',
          example: ResponseCode::SERVER_ERROR->value,
        ),
        new OA\Property(
          property: 'errors',
          description: 'Список ошибок валидации',
          type: 'array',
          items: new OA\Items(ref: '#/components/schemas/ValidationErrorItem'),
          nullable: true,
        ),
      ],
    ),
  ],
)]
final class ApiErrorResponseDto extends ApiResponseDto
{
  public function __construct(
    protected string $message,
    private readonly string $code,
    private readonly int $status,
    private readonly ?array $errors = null,
    protected ?string $timestamp = null,
  ) {
    parent::__construct(success: false, message: $message, timestamp: $timestamp);
    
  }
  
  public function getCode(): string
  {
    return $this->code;
  }
  
  public function getStatus(): int
  {
    return $this->status;
  }
  
  public function getErrors(): ?array
  {
    return $this->errors;
  }
  
  public function jsonSerialize(): array
  {
    $data = parent::jsonSerialize();
    $data['code'] = $this->code;
    $data['status'] = $this->status;
    if ($this->errors !== null) {
      $data['errors'] = array_map(
        static fn ($error) => $error instanceof JsonSerializable ? $error->jsonSerialize() : $error,
        $this->errors,
      );
    }
    
    return $data;
  }
}
