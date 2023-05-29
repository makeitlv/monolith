<?php

declare(strict_types=1);

namespace App\Notification\Application\UseCase\Event\Admin\AdminCreated;

use App\Admin\Domain\Event\AdminCreatedEvent as Event;
use App\Common\Domain\Bus\Event\EventHandler;
use App\Notification\Application\Service\Admin\WelcomeNotifierInterface;
use App\Notification\Domain\Event\Admin\AdminCreatedEvent;

readonly final class SendWelcomeNotifierHandler implements EventHandler
{
    public function __construct(private WelcomeNotifierInterface $notifier)
    {
    }

    public function __invoke(Event $event)
    {
        $this->notifier->notify(
            new AdminCreatedEvent(
                $event->uuid,
                $event->email,
                $event->name,
                $event->plainPassword,
                $event->confirmationToken
            )
        );
    }
}
