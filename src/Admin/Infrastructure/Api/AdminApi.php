<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Api;

use App\Admin\Application\UseCase\Command\Activate\ActivateAdminCommand;
use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Admin\Application\UseCase\Query\FindByConfirmationToken\FindByConfirmationTokenQuery;
use App\Admin\Domain\DataTransfer\AdminDTO;
use App\Admin\Infrastructure\Api\Exception\AdminNotFoundException;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Common\Domain\Bus\Query\QueryBus;

final class AdminApi
{
    public function __construct(private QueryBus $queryBus, private CommandBus $commandBus)
    {
    }

    public function findByEmail(string $email): ?AdminDTO
    {
        /** @var AdminDTO $admin */
        $admin = $this->queryBus->handle(new FindByEmailQuery($email));

        return $admin;
    }

    public function activateAdmin(string $confirmationToken): void
    {
        /** @var AdminDTO|null $admin */
        $admin = $this->queryBus->handle(new FindByConfirmationTokenQuery($confirmationToken));
        if ($admin === null) {
            throw new AdminNotFoundException();
        }

        $this->commandBus->dispatch(new ActivateAdminCommand($admin->uuid));
    }
}
