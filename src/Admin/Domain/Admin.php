<?php

declare(strict_types=1);

namespace App\Admin\Domain;

use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use DateTimeImmutable;
use DomainException;

final class Admin
{
    public function __construct(
        private string $uuid,
        private string $email,
        private string $firstname,
        private string $lastname,
        private string $password,
        private Role $role,
        private Status $status,
        private DateTimeImmutable $createdAt = new DateTimeImmutable(),
        private ?DateTimeImmutable $updatedAt = null,
        private ?string $confirmationToken = null
    ) {
    }

    public function update(string $email, string $firstname, string $lastname): void
    {
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;

        $this->updatedAt = new DateTimeImmutable();
    }

    public function updatePassword(string $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        if ($this->status === Status::ACTIVATED) {
            throw new DomainException("Admin is already activated.");
        }

        $this->status = Status::ACTIVATED;
        $this->confirmationToken = null;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function block(): void
    {
        if ($this->status === Status::BLOCKED) {
            throw new DomainException("Admin is already blocked.");
        }

        $this->status = Status::BLOCKED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function unblock(): void
    {
        if ($this->status !== Status::BLOCKED) {
            throw new DomainException("Admin is not blocked.");
        }

        $this->status = $this->confirmationToken === null ? Status::ACTIVATED : Status::BLOCKED;

        $this->updatedAt = new DateTimeImmutable();
    }

    public function canBeDeleted(): bool
    {
        return $this->status === Status::BLOCKED;
    }

    public function equals(Admin $admin): bool
    {
        return $this->email === $admin->email;
    }
}
