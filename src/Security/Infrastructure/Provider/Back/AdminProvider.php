<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Provider\Back;

use App\Security\Infrastructure\Adapter\Admin\AdminAdapter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AdminProvider implements UserProviderInterface
{
    public function __construct(private AdminAdapter $adapter)
    {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $admin = $this->loadUserByIdentifier($user->getUserIdentifier());

        if ($admin instanceof AdminIdentity) {
            return $admin;
        }

        throw new UnsupportedUserException();
    }

    public function supportsClass(string $class): bool
    {
        return AdminIdentity::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $admin = $this->adapter->findAdminByEmail($identifier);
        if ($admin === null) {
            throw new UserNotFoundException();
        }

        $adminIdentity = new AdminIdentity(
            $admin->uuid,
            $admin->email,
            $admin->firstname,
            $admin->lastname,
            $admin->password,
            $admin->role,
            $admin->status,
            $admin->passwordSecure
        );

        if ($adminIdentity->getStatus() !== "activated") {
            throw new UnsupportedUserException();
        }

        return $adminIdentity;
    }
}
