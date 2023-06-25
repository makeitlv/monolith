<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter\Security;

use App\Admin\Domain\DataTransfer\AdminDTO;
use App\Security\Infrastructure\Api\Back\SecurityApi;

final class SecurityAdapter
{
    public function __construct(private SecurityApi $api)
    {
    }

    public function getAdmin(): AdminDTO
    {
        $admin = $this->api->getAdmin();

        return new AdminDTO(
            $admin->uuid,
            $admin->email,
            $admin->firstname,
            $admin->lastname,
            $admin->password,
            $admin->role,
            $admin->status,
            $admin->passwordSecure
        );
    }
}
