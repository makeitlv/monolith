<?php

declare(strict_types=1);

namespace App\Admin\Domain\DataTransfer;

readonly final class AdminDTO
{
    public function __construct(
        public string $uuid,
        public string $email,
        public string $firstname,
        public string $lastname,
        public string $password,
        public string $role,
        public string $status
    ) {
    }
}
