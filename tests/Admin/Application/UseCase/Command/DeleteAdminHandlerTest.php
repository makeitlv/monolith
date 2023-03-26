<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Admin\Application\UseCase\Command\Block\BlockAdminCommand;
use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteAdminCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

final class DeleteAdminHandlerTest extends KernelTestCase
{
    public function testAdminSuccessfullyDeleted(): void
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

        $commandBus->dispatch(new BlockAdminCommand($uuid));
        $commandBus->dispatch(new DeleteAdminCommand($uuid));

        /** @var EntityRepository $repository */
        $repository = self::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(Admin::class);

        $admin = $repository->find($uuid);

        self::assertNotInstanceOf(Admin::class, $admin);
        self::assertNull($admin);
    }

    public function testOnlyABlockedAdminCanByDeleted(): void
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

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Cannot delete admin. Admin is not blocked!");

        $commandBus->dispatch(new DeleteAdminCommand($uuid));
    }
}
