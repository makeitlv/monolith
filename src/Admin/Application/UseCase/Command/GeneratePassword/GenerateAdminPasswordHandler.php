<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\GeneratePassword;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Event\Internal\AdminPasswordChangedEvent;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Admin\Domain\Service\PasswordGeneratorInterface;
use App\Admin\Domain\Service\PasswordEncoderInterface;
use App\Common\Domain\Bus\Command\CommandHandler;
use App\Common\Domain\Bus\Event\EventBus;
use App\Common\Domain\Exception\DomainException;
use App\Common\Domain\Translation\TranslatableMessage;

readonly final class GenerateAdminPasswordHandler implements CommandHandler
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordGeneratorInterface $passwordGenerator,
        private PasswordEncoderInterface $passwordEncoder,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(GenerateAdminPasswordCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if (!$admin instanceof Admin) {
            throw new DomainException(
                new TranslatableMessage("Admin not found! Uuid: %uuid%.", ["%uuid%" => $command->uuid])
            );
        }

        $password = $this->passwordGenerator->generate();
        $admin->updatePassword($this->passwordEncoder->encode($password));
        $admin->corruptPassword();

        $this->adminRepository->persist($admin);

        $this->eventBus->publish(
            new AdminPasswordChangedEvent(
                $command->uuid,
                $command->email,
                $command->firstname . " " . $command->lastname,
                $password
            )
        );
    }
}
