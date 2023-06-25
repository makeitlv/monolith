<?php

declare(strict_types=1);

namespace App\Notification\Application\UseCase\Event\External\Admin\AdminCreated;

use App\Common\Domain\Bus\Event\EventHandler;
use App\Notification\Application\Service\NotifierInterface;
use App\Notification\Domain\Event\External\Admin\AdminCreatedEvent;

readonly final class SendWelcomeNotifierHandler implements EventHandler
{
    public function __construct(private NotifierInterface $notifier)
    {
    }

    public function __invoke(AdminCreatedEvent $event)
    {
        $this->notifier->notify($event);
    }
}
