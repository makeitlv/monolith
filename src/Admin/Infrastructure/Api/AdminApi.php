<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Api;

use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Admin\Domain\DataTransfer\AdminDTO;
use App\Common\Domain\Bus\Query\QueryBus;

final class AdminApi
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function findByEmail(string $email): ?AdminDTO
    {
        /** @var AdminDTO $admin */
        $admin = $this->queryBus->handle(new FindByEmailQuery($email));

        return $admin;
    }
}
