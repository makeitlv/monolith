<?php

declare(strict_types=1);

namespace App\Notification\Application\Service\Admin;

use App\Notification\Domain\Event\External\Admin\AdminPasswordChangedEvent;

interface PasswordNotifierInterface
{
    public function notify(AdminPasswordChangedEvent $event): void;
}
