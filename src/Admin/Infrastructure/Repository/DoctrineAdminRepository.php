<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Repository;

use App\Admin\Domain\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Infrastructure\Repository\AbstractDoctrineRepository;

final class DoctrineAdminRepository extends AbstractDoctrineRepository implements AdminRepositoryInterface
{
    protected const CLASS_NAME = Admin::class;

    public function persist(Admin $admin): void
    {
        $this->entityManager->persist($admin);
    }

    public function remove(Admin $admin): void
    {
        $this->entityManager->remove($admin);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function findByUuid(string $uuid): ?Admin
    {
        $admin = $this->objectRepository->findOneBy(["uuid" => $uuid]);

        if (!$admin instanceof Admin) {
            return null;
        }

        return $admin;
    }

    public function findByEmail(string $email): ?Admin
    {
        $admin = $this->objectRepository->findOneBy(["email" => $email]);

        if (!$admin instanceof Admin) {
            return null;
        }

        return $admin;
    }
}
