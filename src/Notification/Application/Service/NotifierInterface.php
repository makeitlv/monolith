<?php

declare(strict_types=1);

namespace App\Notification\Application\Service;

use App\Common\Domain\Bus\Event\Event;

interface NotifierInterface
{
    public function notify(Event $event): void;
}
