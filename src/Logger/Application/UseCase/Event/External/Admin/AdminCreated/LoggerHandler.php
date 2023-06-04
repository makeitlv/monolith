<?php

declare(strict_types=1);

namespace App\Logger\Application\UseCase\Event\External\Admin\AdminCreated;

use App\Common\Domain\Bus\Event\EventHandler;
use App\Logger\Domain\Event\External\Admin\AdminCreatedEvent;

readonly final class LoggerHandler implements EventHandler
{
    public function __invoke(AdminCreatedEvent $event)
    {
        dump($event);
    }
}
