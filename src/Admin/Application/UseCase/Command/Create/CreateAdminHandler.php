<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Create;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use App\Admin\Domain\Repository\AdminRepository;
use App\Admin\Domain\Service\ConfirmationTokenGenerator;
use App\Admin\Domain\Service\PasswordGenerator;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class CreateAdminHandler implements CommandHandler
{
    public function __construct(
        private AdminRepository $adminRepository,
        private PasswordGenerator $passwordGenerator,
        private ConfirmationTokenGenerator $confirmationTokenGenerator
    ) {
    }

    public function __invoke(CreateAdminCommand $command): void
    {
        if ($this->adminRepository->findByEmail($command->email)) {
            throw new DomainException(
                sprintf(
                    "Admin already exists with such email %s.",
                    $command->email
                )
            );
        }

        $admin = new Admin(
            $command->uuid,
            $command->email,
            $command->firstname,
            $command->lastname,
            $this->passwordGenerator->generate(),
            Role::ROLE_ADMIN,
            Status::DISABLED,
            confirmationToken: $this->confirmationTokenGenerator->generate()
        );

        $this->adminRepository->persist($admin);
    }
}
