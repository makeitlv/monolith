<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\CreateSuper;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use App\Admin\Domain\Repository\AdminRepository;
use App\Admin\Domain\Service\PasswordEncoder;
use App\Common\Domain\Bus\Command\CommandHandler;
use DomainException;

readonly final class CreateSuperAdminHandler implements CommandHandler
{
    public function __construct(
        private AdminRepository $adminRepository,
        private PasswordEncoder $passwordEncoder
    ) {
    }

    public function __invoke(CreateSuperAdminCommand $command): void
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
            $this->passwordEncoder->encode($command->password),
            Role::ROLE_SUPER_ADMIN,
            Status::ACTIVATED
        );

        $this->adminRepository->persist($admin);
    }
}
