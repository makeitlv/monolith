<?php

declare(strict_types=1);

namespace App\Notification\Application\UseCase\Event\External\Admin\AdminPasswordChanged;

use App\Common\Domain\Bus\Event\EventHandler;
use App\Notification\Application\Service\NotifierInterface;
use App\Notification\Domain\Event\External\Admin\AdminPasswordChangedEvent;

readonly final class SendChangedPasswordNotifierHandler implements EventHandler
{
    public function __construct(private NotifierInterface $notifier)
    {
    }

    public function __invoke(AdminPasswordChangedEvent $event)
    {
        $this->notifier->notify($event);
    }
}
