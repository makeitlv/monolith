<?php

declare(strict_types=1);

namespace App\Common\Domain\Bus\Event;

interface EventBus
{
    public function publish(object $event): void;
}
