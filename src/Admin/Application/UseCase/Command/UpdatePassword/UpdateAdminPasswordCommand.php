<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\UpdatePassword;

use App\Common\Domain\Bus\Command\Command;

readonly final class UpdateAdminPasswordCommand implements Command
{
    public function __construct(public string $uuid, public string $password)
    {
    }
}
