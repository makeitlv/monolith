<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Unblock;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class UnblockAdminHandler implements CommandHandler
{
    public function __construct(private AdminRepositoryInterface $adminRepository)
    {
    }

    public function __invoke(UnblockAdminCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(sprintf("Admin not found! Uuid: %s.", $command->uuid));
        }

        $admin->unblock();

        $this->adminRepository->persist($admin);
    }
}