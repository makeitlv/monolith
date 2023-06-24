<?php

declare(strict_types=1);

namespace App\Notification\Application\UseCase\Event\External\Admin\AdminCreated;

use App\Common\Domain\Bus\Event\EventHandler;
use App\Notification\Application\Service\Admin\PasswordNotifierInterface;
use App\Notification\Domain\Event\External\Admin\AdminPasswordChangedEvent;

readonly final class SendPasswordNotifierHandler implements EventHandler
{
    public function __construct(private PasswordNotifierInterface $notifier)
    {
    }

    public function __invoke(AdminPasswordChangedEvent $event)
    {
        $this->notifier->notify($event);
    }
}
