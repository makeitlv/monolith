<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;

final class UpdateAdminPasswordTest extends TestCase
{
    public function testAdminPasswordUpdated(): void
    {
        $admin = new Admin(
            "uuid",
            "test@test.com",
            "test",
            "test",
            ($password = "password"),
            Role::ROLE_ADMIN,
            Status::DISABLED
        );

        $admin->updatePassword("newPassword");

        $closure = function () use ($admin) {
            return [$admin->password, $admin->updatedAt];
        };

        list($newPassword, $updatedAt) = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertNotEquals($password, $newPassword);
        self::assertNotNull($updatedAt);
    }
}
