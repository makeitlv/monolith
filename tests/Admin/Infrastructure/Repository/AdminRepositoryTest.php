<?php

declare(strict_types=1);

namespace App\Testes\Admin\Infrastructure\Repository;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AdminRepositoryTest extends KernelTestCase
{
    public function testFindByEmail(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()
            ->get("doctrine")
            ->getManager();

        $admin = new Admin(
            "de28e1d2-9a57-48b0-af1b-ae54b39e0801",
            ($email = "test@test.com"),
            "test",
            "test",
            "password",
            Role::ROLE_ADMIN,
            Status::DISABLED
        );

        $entityManager->persist($admin);
        $entityManager->flush();

        /** @var AdminRepositoryInterface $repository */
        $repository = self::getContainer()->get(AdminRepositoryInterface::class);

        $admin = $repository->findByEmail($email);

        self::assertInstanceOf(Admin::class, $admin);
    }

    public function testNotFoundByEmail(): void
    {
        /** @var AdminRepositoryInterface $repository */
        $repository = self::getContainer()->get(AdminRepositoryInterface::class);

        $admin = $repository->findByEmail("test@test.com");

        self::assertNotInstanceOf(Admin::class, $admin);
    }

    public function testFindByUuid(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()
            ->get("doctrine")
            ->getManager();

        $admin = new Admin(
            ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
            "test@test.com",
            "test",
            "test",
            "password",
            Role::ROLE_ADMIN,
            Status::DISABLED
        );

        $entityManager->persist($admin);
        $entityManager->flush();

        /** @var AdminRepositoryInterface $repository */
        $repository = self::getContainer()->get(AdminRepositoryInterface::class);

        $admin = $repository->findByUuid($uuid);

        self::assertInstanceOf(Admin::class, $admin);
    }

    public function testNotFoundByUuid(): void
    {
        /** @var AdminRepositoryInterface $repository */
        $repository = self::getContainer()->get(AdminRepositoryInterface::class);

        $admin = $repository->findByEmail("de28e1d2-9a57-48b0-af1b-ae54b39e0801");

        self::assertNotInstanceOf(Admin::class, $admin);
    }

    public function testPersist(): void
    {
        $admin = new Admin(
            ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
            "test@test.com",
            "test",
            "test",
            "password",
            Role::ROLE_ADMIN,
            Status::DISABLED
        );

        /** @var AdminRepositoryInterface $repository */
        $repository = self::getContainer()->get(AdminRepositoryInterface::class);
        $repository->persist($admin);
        $repository->flush();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()
            ->get("doctrine")
            ->getManager();

        $admin = $entityManager->getRepository(Admin::class)->findOneBy(["uuid" => $uuid]);

        self::assertInstanceOf(Admin::class, $admin);
    }

    public function testRemove(): void
    {
        $admin = new Admin(
            ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
            "test@test.com",
            "test",
            "test",
            "password",
            Role::ROLE_ADMIN,
            Status::DISABLED
        );

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()
            ->get("doctrine")
            ->getManager();

        $entityManager->persist($admin);
        $entityManager->flush();

        $admin = $entityManager->getRepository(Admin::class)->findOneBy(["uuid" => $uuid]);

        self::assertInstanceOf(Admin::class, $admin);

        /** @var AdminRepositoryInterface $repository */
        $repository = self::getContainer()->get(AdminRepositoryInterface::class);
        $repository->remove($admin);
        $repository->flush();

        $admin = $entityManager->getRepository(Admin::class)->findOneBy(["uuid" => $uuid]);

        self::assertNull($admin);
    }
}
