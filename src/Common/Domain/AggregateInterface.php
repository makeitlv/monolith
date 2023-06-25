<?php

declare(strict_types=1);

namespace App\Common\Domain;

use App\Common\Domain\Bus\Event\Event;

interface AggregateInterface
{
    /**
     * @return Event[]
     */
    public function popEvents(): array;
}
