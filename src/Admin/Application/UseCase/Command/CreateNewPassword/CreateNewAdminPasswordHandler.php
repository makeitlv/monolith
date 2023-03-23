<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\NewPassword;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Admin\Domain\Service\PasswordGeneratorInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class NewAdminPasswordHandler implements CommandHandler
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordGeneratorInterface $passwordGenerator
    ) {
    }

    public function __invoke(CreateNewAdminPasswordCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(sprintf("Admin not found! Uuid: %s.", $command->uuid));
        }

        $admin->updatePassword($this->passwordGenerator->generate());

        $this->adminRepository->persist($admin);
    }
}
