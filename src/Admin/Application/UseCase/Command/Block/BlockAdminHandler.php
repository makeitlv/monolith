<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Block;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepository;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class BlockAdminHandler implements CommandHandler
{
    public function __construct(private AdminRepository $adminRepository)
    {
    }

    public function __invoke(BlockAdminCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(
                sprintf("Admin not found! Uuid: %s.", $command->uuid)
            );
        }

        $admin->block();

        $this->adminRepository->persist($admin);
    }
}
