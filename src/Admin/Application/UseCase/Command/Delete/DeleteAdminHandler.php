<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Delete;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Translation\TranslatableMessage;

readonly final class DeleteAdminHandler implements CommandHandler
{
    public function __construct(private AdminRepositoryInterface $adminRepository)
    {
    }

    public function __invoke(DeleteAdminCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(
                new TranslatableMessage("Admin not found! Uuid: %uuid%.", ["%uuid%" => $command->uuid], "domain")
            );
        }

        if (!$admin->canBeDeleted()) {
            throw new DomainException(
                new TranslatableMessage("Cannot delete admin. Admin is not blocked!", [], "domain")
            );
        }

        $this->adminRepository->remove($admin);
    }
}
