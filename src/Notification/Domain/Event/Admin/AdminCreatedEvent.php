<?php

declare(strict_types=1);

namespace App\Notification\Domain\Event\Admin;

readonly final class AdminCreatedEvent
{
    public function __construct(
        public string $uuid,
        public string $email,
        public string $name,
        public string $plainPassword,
        public ?string $confirmationToken = null
    ) {
    }
}
