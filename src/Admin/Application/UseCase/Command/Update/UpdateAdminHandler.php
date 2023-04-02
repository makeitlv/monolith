<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Update;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Translation\TranslatableMessage;

readonly final class UpdateAdminHandler implements CommandHandler
{
    public function __construct(private AdminRepositoryInterface $adminRepository)
    {
    }

    public function __invoke(UpdateAdminCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(
                new TranslatableMessage("Admin not found! Uuid: %uuid%.", ["%uuid%" => $command->uuid])
            );
        }

        $existingAdmin = $this->adminRepository->findByEmail($command->email);
        if ($existingAdmin && $admin->equals($existingAdmin) === false) {
            throw new DomainException(
                new TranslatableMessage("Admin already exists with such email %email%.", [
                    "%email%" => $command->email,
                ])
            );
        }

        $admin->update($command->email, $command->firstname, $command->lastname);

        $this->adminRepository->persist($admin);
    }
}
