<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\NewPassword;

use App\Common\Domain\Bus\Command\Command;

readonly final class CreateNewAdminPasswordCommand implements Command
{
    public function __construct(public string $uuid)
    {
    }
}
