<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class CreateAdminHandlerTest extends KernelTestCase
{
    public function testAdminSuccessfullyCreated(): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = self::getContainer()->get(CommandBus::class);

        $commandBus->dispatch(
            new CreateAdminCommand(
                ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
                ($email = "admin@admin.com"),
                ($firstname = "Admin"),
                ($lastname = "Admin")
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
                $admin->password,
                $admin->role,
                $admin->status,
                $admin->createdAt,
                $admin->updatedAt,
                $admin->confirmationToken,
            ];
        };

        list(
            $actualUuid,
            $actualEmail,
            $actualFirstname,
            $actualLastname,
            $password,
            $role,
            $status,
            $createdAt,
            $updatedAt,
            $confirmationToken,
        ) = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertEquals($uuid, $actualUuid);
        self::assertEquals($email, $actualEmail);
        self::assertEquals($firstname, $actualFirstname);
        self::assertEquals($lastname, $actualLastname);
        self::assertEquals(60, strlen($password));
        self::assertEquals(Role::ROLE_ADMIN, $role);
        self::assertEquals(Status::DISABLED, $status);
        self::assertNotNull($createdAt);
        self::assertNull($updatedAt);
        self::assertNotNull($confirmationToken);
    }

    public function testAdminWithSuchEmailAlreadyExist(): void
    {
        /** @var CommandBus $commandBus */
        $commandBus = self::getContainer()->get(CommandBus::class);

        $commandBus->dispatch(
            new CreateAdminCommand("de28e1d2-9a57-48b0-af1b-ae54b39e0801", "admin@admin.com", "Admin", "Admin")
        );

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin already exists with such email admin@admin.com");

        $commandBus->dispatch(
            new CreateAdminCommand("de28e1d2-9a57-48b0-af1b-ae54b39e0802", "admin@admin.com", "Admin", "Admin")
        );
    }
}
