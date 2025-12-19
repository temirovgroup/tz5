<?php

declare(strict_types=1);

namespace api\enums;

use ReflectionClass;

trait EnumToArrayTrait
{
  private static function names(): array
  {
    $traits = (new ReflectionClass(
      objectOrClass: self::class,
    ))->getTraitNames();

    if ($traits !== [] && in_array(FriendlyUserTrait::class, $traits, true)) {
      $list = [];
      foreach (self::cases() as $case) {
        $list[] = $case->friendly();
      }

      return $list;
    }

    return array_column(self::cases(), 'name');
  }

  public static function getName(string|int $value): ?string
  {
    $names = static::array();

    return $names[$value] ?? null;
  }

  public static function values(): array
  {
    return array_column(self::cases(), 'value');
  }

  public static function array(): array
  {
    return array_combine(self::values(), self::names());
  }
}
