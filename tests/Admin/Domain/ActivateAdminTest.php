<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;
use DomainException;

final class ActivateAdminTest extends TestCase
{
    public function testAdminSuccessfullyActivated(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::DISABLED);

        $admin->activate();

        $closure = function () use ($admin) {
            return $admin->status;
        };

        $status = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertEquals(Status::ACTIVATED, $status);
    }

    public function testAdminAlreadyActivated(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::ACTIVATED);

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin is already activated.");

        $admin->activate();
    }

    public function testAdminBlocked(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::BLOCKED);

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin is blocked.");

        $admin->activate();
    }
}
