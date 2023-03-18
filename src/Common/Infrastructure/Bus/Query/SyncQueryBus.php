<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Query;

use App\Common\Domain\Bus\Query\Query;
use App\Common\Domain\Bus\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class SyncQueryBus implements QueryBus
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function handle(Query $query): mixed
    {
        return $this->handleQuery($query);
    }
}
