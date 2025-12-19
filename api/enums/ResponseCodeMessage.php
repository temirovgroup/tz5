<?php

declare(strict_types=1);

namespace api\enums;


enum ResponseCodeMessage: string
{
  use EnumToArrayTrait;
  case PAYMENT_REQUIRED = 'Payment required';
  case SUCCESS = 'Successful request';
  case CREATED = 'Resource created';
  case CONFLICT = 'Conflict';
  case NO_CONTENT = 'No content';
  case BAD_REQUEST = 'Bad request';
  case UNAUTHORIZED = 'Authorization required';
  case FORBIDDEN = 'Access forbidden';
  case NOT_FOUND = 'Resource not found';
  case TOO_MANY_REQUESTS = 'Too many requests';
  case VALIDATION_ERROR = 'Validation error';
  case METHOD_NOT_ALLOWED = 'Method not allowed';
  case SERVER_ERROR = 'Internal server error';
  case BAD_GATEWAY = 'Bad gateway';
  case SERVICE_UNAVAILABLE = 'Service unavailable';
}
