<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Common\Domain\Bus\Command\CommandBus;
use App\Admin\Application\UseCase\Command\Block\BlockAdminCommand;
use App\Admin\Application\UseCase\Command\Create\CreateAdminCommand;
use App\Admin\Domain\ValueObject\Status;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

final class BlockAdminHandlerTest extends KernelTestCase
{
    public function testAdminSuccessfullyBlocked(): void
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

        /** @var EntityRepository $repository */
        $repository = self::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(Admin::class);

        $admin = $repository->find($uuid);

        $closure = function () use ($admin) {
            return $admin->status;
        };

        $status = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertEquals(Status::BLOCKED, $status);
    }

    public function testAdminAlreadyBlocked(): void
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

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin is already blocked.");

        $commandBus->dispatch(new BlockAdminCommand($uuid));
    }
}
