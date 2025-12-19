<?php

declare(strict_types=1);

namespace api\enums;

use api\attributes\FriendlyUser;
use ReflectionClassConstant;

trait FriendlyUserTrait
{
  public function friendly(): string
  {
    $attributes = (new ReflectionClassConstant(
      class: self::class,
      constant: $this->name,
    ))->getAttributes(
      name: FriendlyUser::class,
    );

    if ($attributes === []) {
      return $this->name;
    }

    return $attributes[0]->newInstance()->getFriendly();
  }
}
