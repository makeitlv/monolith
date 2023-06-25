<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Application\UseCase\Command\UpdatePassword\UpdateAdminPasswordCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateAdminPasswordHandlerTest extends KernelTestCase
{
    public function testAdminPasswordSuccessfullyUpdated(): void
    {
        /** @var EntityRepository $repository */
        $repository = self::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(Admin::class);

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

        $admin = $repository->find($uuid);
        $closure = function () use ($admin) {
            return $admin->password;
        };
        $oldPassword = call_user_func($closure->bindTo($admin, $admin::class));

        $commandBus->dispatch(new UpdateAdminPasswordCommand("de28e1d2-9a57-48b0-af1b-ae54b39e0801", "newPassword"));

        $admin = $repository->find($uuid);
        $closure = function () use ($admin) {
            return [$admin->password, $admin->passwordSecure];
        };
        list($newPassword, $passwordSecure) = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertNotEquals($oldPassword, $newPassword);
        self::assertTrue($passwordSecure);
    }
}
