<?php

declare(strict_types=1);

namespace App\Admin\Domain\ValueObject;

enum Status: string
{
    case ACTIVATED = "activated";
    case DISABLED = "disabled";
    case BLOCKED = "blocked";
}
