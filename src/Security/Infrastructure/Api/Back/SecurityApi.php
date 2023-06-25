<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Api\Back;

use App\Security\Domain\DataTransfer\AdminDTO;
use App\Security\Infrastructure\Provider\Back\AdminIdentity;
use Symfony\Bundle\SecurityBundle\Security;
use RuntimeException;

final class SecurityApi
{
    public function __construct(private Security $security)
    {
    }

    public function getAdmin(): AdminDTO
    {
        $admin = $this->security->getUser();
        if ($admin instanceof AdminIdentity) {
            return new AdminDTO(
                $admin->getUuid(),
                $admin->getEmail(),
                $admin->getFirstname(),
                $admin->getLastname(),
                (string) $admin->getPassword(),
                $admin->getRole(),
                $admin->getStatus(),
                $admin->isPasswordSecure()
            );
        }

        throw new RuntimeException("Back user is not logged in.");
    }
}
