<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Command;

use App\Common\Domain\Bus\Command\Command;
use App\Common\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Throwable;

class SyncCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
