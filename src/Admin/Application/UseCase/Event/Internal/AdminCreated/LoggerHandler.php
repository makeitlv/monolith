<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Event\Internal\AdminCreated;

use App\Admin\Domain\Event\Internal\AdminCreatedEvent;
use App\Common\Domain\Bus\Event\EventHandler;

readonly final class LoggerHandler implements EventHandler
{
    public function __invoke(AdminCreatedEvent $event)
    {
        dump("--------------TEST-------------");
    }
}
