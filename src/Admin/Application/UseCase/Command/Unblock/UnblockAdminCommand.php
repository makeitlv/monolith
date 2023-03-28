<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Unblock;

use App\Common\Domain\Bus\Command\Command;

readonly final class UnblockAdminCommand implements Command
{
    public function __construct(public string $uuid)
    {
    }
}
