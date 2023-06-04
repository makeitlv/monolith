<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Event;

use App\Common\Domain\Bus\Event\Event;
use App\Common\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use RuntimeException;
use Throwable;

class AsyncEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(object $event): void
    {
        if (!$event instanceof Envelope && !$event instanceof Event) {
            throw new RuntimeException("Only Event::class and Envelope::class can be dispatched!");
        }

        try {
            $this->eventBus->dispatch($event);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
