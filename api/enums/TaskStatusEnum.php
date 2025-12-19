<?php

declare(strict_types=1);

namespace api\enums;

use api\attributes\FriendlyUser;

enum TaskStatusEnum: int
{
  use EnumToArrayTrait;
  use FriendlyUserTrait;
  
  #[FriendlyUser('New')]
  case STATUS_NEW = 10;
  #[FriendlyUser('In Progress')]
  case STATUS_IN_PROGRESS = 20;
  #[FriendlyUser('Completed')]
  case STATUS_COMPLETED = 30;
}
