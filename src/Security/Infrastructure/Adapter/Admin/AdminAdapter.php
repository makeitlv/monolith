<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Adapter\Admin;

use App\Admin\Infrastructure\Api\AdminApi;
use App\Security\Domain\DataTransfer\AdminDTO;

final class AdminAdapter
{
    public function __construct(private AdminApi $api)
    {
    }

    public function findAdminByEmail(string $email): ?AdminDTO
    {
        $admin = $this->api->findByEmail($email);

        if ($admin === null) {
            return null;
        }

        return new AdminDTO(
            $admin->uuid,
            $admin->email,
            $admin->firstname,
            $admin->lastname,
            $admin->password,
            $admin->role,
            $admin->status
        );
    }

    public function activateAdmin(string $confirmationToken): void
    {
        $this->api->activateAdmin($confirmationToken);
    }
}
