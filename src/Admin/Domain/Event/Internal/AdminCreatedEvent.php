<?php

declare(strict_types=1);

namespace App\Admin\Domain\Event\Internal;

use App\Common\Domain\Bus\Event\Event;

readonly final class AdminCreatedEvent implements Event
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
