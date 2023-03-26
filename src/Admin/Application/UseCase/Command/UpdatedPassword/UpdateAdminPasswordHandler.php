<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\UpdatePassword;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Admin\Domain\Service\PasswordGeneratorInterface;
use App\Admin\Domain\Service\PasswordEncoderInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class UpdateAdminPasswordHandler implements CommandHandler
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordGeneratorInterface $passwordGenerator,
        private PasswordEncoderInterface $passwordEncoder
    ) {
    }

    public function __invoke(UpdateAdminPasswordCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(sprintf("Admin not found! Uuid: %s.", $command->uuid));
        }

        $password = $this->passwordGenerator->generate();
        $admin->updatePassword($this->passwordEncoder->encode($password));

        $this->adminRepository->persist($admin);
    }
}
