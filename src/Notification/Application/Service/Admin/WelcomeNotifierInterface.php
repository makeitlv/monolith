<?php

declare(strict_types=1);

namespace App\Notification\Application\Service\Admin;

use App\Notification\Domain\Event\External\Admin\AdminCreatedEvent;

interface WelcomeNotifierInterface
{
    public function notify(AdminCreatedEvent $event): void;
}
