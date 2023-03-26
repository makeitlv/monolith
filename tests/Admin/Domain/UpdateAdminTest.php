<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Domain\Admin;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;

final class UpdateAdminTest extends TestCase
{
    public function testAdminUpdated(): void
    {
        $admin = new Admin(
            "uuid",
            ($email = "test@test.com"),
            ($firstname = "test"),
            ($lastname = "test"),
            "password",
            Role::ROLE_ADMIN,
            Status::DISABLED
        );

        $admin->update("new@email.com", "firstname", "lastname");

        $closure = function () use ($admin) {
            return [$admin->email, $admin->firstname, $admin->lastname];
        };

        list($newEmail, $newFirstname, $newLastname) = call_user_func($closure->bindTo($admin, $admin::class));

        self::assertNotEquals($email, $newEmail);
        self::assertNotEquals($firstname, $newFirstname);
        self::assertNotEquals($lastname, $newLastname);

        self::assertEquals("new@email.com", $newEmail);
        self::assertEquals("firstname", $newFirstname);
        self::assertEquals("lastname", $newLastname);
    }
}
