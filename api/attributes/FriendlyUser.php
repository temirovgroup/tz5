<?php

declare(strict_types=1);

namespace api\attributes;

use api\modules\Module;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final class FriendlyUser
{
  public function __construct(
    private string $friendly,
    private readonly ?string $category = null,
  )
  {
    /*if ($this->category !== null) {
      $this->friendly = Module::t($this->category, $this->friendly);
    }*/
  }

  public function getFriendly(): string
  {
    return $this->friendly;
  }
}
