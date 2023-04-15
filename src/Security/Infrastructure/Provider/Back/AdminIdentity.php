<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Provider\Back;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminIdentity implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private string $uuid,
        private string $email,
        private string $password,
        private string $role,
        private string $status
    ) {
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
