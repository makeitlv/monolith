<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Create;

use App\Common\Domain\Bus\Command\Command;

readonly final class CreateAdminCommand implements Command
{
    public function __construct(
        public string $uuid,
        public string $email,
        public string $firstname,
        public string $lastname
    ) {
    }
}
