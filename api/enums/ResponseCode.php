<?php

declare(strict_types=1);

namespace api\enums;


use api\attributes\FriendlyUser;

enum ResponseCode: int
{
  use EnumToArrayTrait;
  use FriendlyUserTrait;

  #[FriendlyUser(ResponseCodeMessage::SUCCESS->value)]
  case SUCCESS = 200;

  #[FriendlyUser(ResponseCodeMessage::PAYMENT_REQUIRED->value)]
  case PAYMENT_REQUIRED = 402;

  #[FriendlyUser(ResponseCodeMessage::CREATED->value)]
  case CREATED = 201;

  #[FriendlyUser(ResponseCodeMessage::NO_CONTENT->value)]
  case NO_CONTENT = 204;

  #[FriendlyUser(ResponseCodeMessage::BAD_REQUEST->value)]
  case BAD_REQUEST = 400;

  #[FriendlyUser(ResponseCodeMessage::UNAUTHORIZED->value)]
  case UNAUTHORIZED = 401;

  #[FriendlyUser(ResponseCodeMessage::FORBIDDEN->value)]
  case FORBIDDEN = 403;

  #[FriendlyUser(ResponseCodeMessage::NOT_FOUND->value)]
  case NOT_FOUND = 404;

  #[FriendlyUser(ResponseCodeMessage::CONFLICT->value)]
  case CONFLICT = 409;

  #[FriendlyUser(ResponseCodeMessage::VALIDATION_ERROR->value)]
  case VALIDATION_ERROR = 422;

  #[FriendlyUser(ResponseCodeMessage::METHOD_NOT_ALLOWED->value)]
  case METHOD_NOT_ALLOWED = 405;

  #[FriendlyUser(ResponseCodeMessage::TOO_MANY_REQUESTS->value)]
  case TOO_MANY_REQUESTS = 429;

  #[FriendlyUser(ResponseCodeMessage::SERVER_ERROR->value)]
  case SERVER_ERROR = 500;

  #[FriendlyUser(ResponseCodeMessage::BAD_GATEWAY->value)]
  case BAD_GATEWAY = 502;

  #[FriendlyUser(ResponseCodeMessage::SERVICE_UNAVAILABLE->value)]
  case SERVICE_UNAVAILABLE = 503;

  public static function getList(): array
  {
    $list = [];
    foreach (self::cases() as $case) {
      $list[] = [
        'code' => $case->value,
        'description' => $case->friendly(),
      ];
    }

    return $list;
  }
}
