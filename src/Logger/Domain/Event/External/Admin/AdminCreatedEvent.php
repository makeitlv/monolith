<?php

declare(strict_types=1);

namespace App\Logger\Domain\Event\External\Admin;

use App\Common\Domain\Bus\Event\Event;

readonly final class AdminCreatedEvent implements Event
{
    public function __construct(public string $uuid, public string $email)
    {
    }
}
