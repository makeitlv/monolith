<?php

declare(strict_types=1);

namespace App\Notification\Application\UseCase\Event\Admin\AdminCreated;

use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Common\Domain\Bus\Event\EventHandler;
use App\Notification\Application\Service\Admin\WelcomeNotifierInterface;
use App\Notification\Domain\Event\Admin\AdminCreatedEvent as AdminAdminCreatedEvent;

readonly final class SendWelcomeNotifierHandler implements EventHandler
{
    public function __construct(private WelcomeNotifierInterface $notifier)
    {
    }

    public function __invoke(AdminCreatedEvent $event)
    {
        $this->notifier->notify(
            new AdminAdminCreatedEvent(
                $event->uuid,
                $event->email,
                $event->name,
                $event->plainPassword,
                $event->confirmationToken
            )
        );
    }
}
