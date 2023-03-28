<?php

declare(strict_types=1);

namespace App\Admin\Domain\Repository;

use App\Admin\Domain\Admin;

interface AdminRepositoryInterface
{
    public function persist(Admin $admin): void;
    public function remove(Admin $admin): void;
    public function flush(): void;
    public function findByUuid(string $uuid): ?Admin;
    public function findByEmail(string $email): ?Admin;
}
