<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\FindByEmail;

use App\Admin\Domain\DataTransfer\AdminDTO;
use App\Admin\Domain\Query\AdminQueryInterface;
use App\Common\Domain\Bus\Query\QueryHandler;

readonly final class FindByEmailHandler implements QueryHandler
{
    public function __construct(private AdminQueryInterface $adminQuery)
    {
    }

    public function __invoke(FindByEmailQuery $query): ?AdminDTO
    {
        return $this->adminQuery->findByEmail($query->email);
    }
}
