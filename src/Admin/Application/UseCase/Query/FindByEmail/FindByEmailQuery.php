<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\FindByEmail;

use App\Common\Domain\Bus\Query\Query;

readonly final class FindByEmailQuery implements Query
{
    public function __construct(public string $email)
    {
    }
}
