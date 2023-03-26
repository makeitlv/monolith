<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;
use DomainException;

final class BlockAdminTest extends TestCase
{
    public function testAdminSuccessfullyBlocked(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::DISABLED);

        $admin->block();

        $closure = function () use ($admin) {
            return $admin->status;
        };

        $status = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertEquals(Status::BLOCKED, $status);
    }

    public function testAdminAlreadyBlocked(): void
    {
        $admin = new Admin("uuid", "test@test.com", "test", "test", "password", Role::ROLE_ADMIN, Status::BLOCKED);

        self::expectException(DomainException::class);
        self::expectExceptionMessage("Admin is already blocked.");

        $admin->block();
    }
}
