<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Update;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepository;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class UpdateAdminHandler implements CommandHandler
{
    public function __construct(private AdminRepository $adminRepository)
    {
    }

    public function __invoke(UpdateAdminCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(
                sprintf("Admin not found! Uuid: %s.", $command->uuid)
            );
        }

        $existingAdmin = $this->adminRepository->findByEmail($command->email);
        if ($existingAdmin && $admin->equals($existingAdmin) === false) {
            throw new DomainException(
                sprintf(
                    "Admin already exists with such email %s.",
                    $command->email
                )
            );
        }

        $admin->update(
            $command->email,
            $command->firstname,
            $command->lastname
        );

        $this->adminRepository->persist($admin);
    }
}
