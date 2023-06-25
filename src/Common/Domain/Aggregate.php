<?php

declare(strict_types=1);

namespace App\Common\Domain;

use App\Common\Domain\Bus\Event\Event;

abstract class Aggregate
{
    /**
     * @var Event[]
     */
    private array $events = [];

    /**
     * @return Event[]
     */
    public function popEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }

    protected function raise(Event $event): void
    {
        $this->events[] = $event;
    }
}
