<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\FindByConfirmationToken;

use App\Common\Domain\Bus\Query\Query;

readonly final class FindByConfirmationTokenQuery implements Query
{
    public function __construct(public string $confirmationToken)
    {
    }
}
