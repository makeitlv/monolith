<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;
use DomainException;

final class UnblockAdminTest extends TestCase
{
    public function testAdminSuccessfullyUnblocked(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::BLOCKED);

        $admin->unblock();

        $closure = function () use ($admin) {
            return $admin->status;
        };

        $status = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertEquals(Status::ACTIVATED, $status);
    }

    public function testAdminNotBlocked(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::ACTIVATED);

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin is not blocked.");

        $admin->unblock();
    }
}
