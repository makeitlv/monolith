<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\CreateSuper;

use App\Common\Domain\Bus\Command\Command;

readonly final class CreateSuperAdminCommand implements Command
{
    public function __construct(
        public string $uuid,
        public string $email,
        public string $firstname,
        public string $lastname,
        public string $password
    ) {
    }
}
