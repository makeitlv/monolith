<?php

declare(strict_types=1);

namespace App\Common\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
