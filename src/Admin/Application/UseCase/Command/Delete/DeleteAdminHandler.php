<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Delete;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepository;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class DeleteAdminHandler implements CommandHandler
{
    public function __construct(private AdminRepository $adminRepository)
    {
    }

    public function __invoke(DeleteAdminCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(
                sprintf("Admin not found! Uuid: %s.", $command->uuid)
            );
        }

        $this->adminRepository->remove($admin);
    }
}
