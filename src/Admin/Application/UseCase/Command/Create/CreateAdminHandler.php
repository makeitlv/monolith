<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Create;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Admin\Domain\Service\ConfirmationTokenGeneratorInterface;
use App\Admin\Domain\Service\PasswordEncoderInterface;
use App\Admin\Domain\Service\PasswordGeneratorInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Translation\TranslatableMessage;

readonly final class CreateAdminHandler implements CommandHandler
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordGeneratorInterface $passwordGenerator,
        private PasswordEncoderInterface $passwordEncoder,
        private ConfirmationTokenGeneratorInterface $confirmationTokenGenerator
    ) {
    }

    public function __invoke(CreateAdminCommand $command): void
    {
        if ($this->adminRepository->findByEmail($command->email)) {
            throw new DomainException(
                new TranslatableMessage("Admin already exists with such email %email%.", [
                    "%email%" => $command->email,
                ])
            );
        }

        $password = $this->passwordGenerator->generate();

        $admin = new Admin(
            $command->uuid,
            $command->email,
            $command->firstname,
            $command->lastname,
            $this->passwordEncoder->encode($password),
            Role::ROLE_ADMIN,
            Status::DISABLED,
            confirmationToken: $this->confirmationTokenGenerator->generate()
        );

        $this->adminRepository->persist($admin);
    }
}
