<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateAdminCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

final class UpdateAdminHandlerTest extends KernelTestCase
{
    public function testAdminSuccessfullyUpdated(): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = self::getContainer()->get(CommandBus::class);

        $commandBus->dispatch(
            new CreateAdminCommand(
                ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
                "admin@admin.com",
                "Admin",
                "Admin"
            )
        );

        $commandBus->dispatch(
            new UpdateAdminCommand(
                "de28e1d2-9a57-48b0-af1b-ae54b39e0801",
                ($newEmail = "newadmin@admin.com"),
                ($newFirstname = "NewAdmin"),
                ($newLastname = "NewAdmin")
            )
        );

        /** @var EntityRepository $repository */
        $repository = self::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(Admin::class);

        $admin = $repository->find($uuid);

        $closure = function () use ($admin) {
            return [
                $admin->uuid,
                $admin->email,
                $admin->firstname,
                $admin->lastname,
                $admin->createdAt,
                $admin->updatedAt,
                $admin->confirmationToken,
            ];
        };

        list($actualUuid, $actualEmail, $actualFirstname, $actualLastname, $createdAt, $updatedAt) = call_user_func(
            $closure->bindTo($admin, $admin::class)
        );

        self::assertEquals($uuid, $actualUuid);
        self::assertEquals($newEmail, $actualEmail);
        self::assertEquals($newFirstname, $actualFirstname);
        self::assertEquals($newLastname, $actualLastname);
        self::assertNotNull($createdAt);
        self::assertNotNull($updatedAt);
    }

    public function testAdminWithSuchEmailAlreadyExist(): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = self::getContainer()->get(CommandBus::class);

        $commandBus->dispatch(
            new CreateAdminCommand("de28e1d2-9a57-48b0-af1b-ae54b39e0801", "admin@admin.com", "Admin", "Admin")
        );

        $commandBus->dispatch(
            new CreateAdminCommand("de28e1d2-9a57-48b0-af1b-ae54b39e0802", "newadmin@admin.com", "Admin", "Admin")
        );

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin already exists with such email newadmin@admin.com.");

        $commandBus->dispatch(
            new UpdateAdminCommand("de28e1d2-9a57-48b0-af1b-ae54b39e0801", "newadmin@admin.com", "NewAdmin", "NewAdmin")
        );
    }
}
