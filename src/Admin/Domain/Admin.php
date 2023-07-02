<?php

declare(strict_types=1);

namespace App\Admin\Domain;

use App\Admin\Domain\Event\Internal\AdminCreatedEvent;
use App\Admin\Domain\Event\Internal\AdminPasswordChangedEvent;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use App\Common\Domain\Aggregate;
use App\Common\Domain\AggregateInterface;
use App\Common\Domain\Translation\TranslatableMessage;
use App\Common\Domain\Exception\DomainException;
use DateTimeImmutable;

class Admin extends Aggregate implements AggregateInterface
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
        private ?string $confirmationToken = null,
        private bool $passwordSecure = false
    ) {
        $this->raise(new AdminCreatedEvent($uuid, $email, $firstname . " " . $lastname, $password, $confirmationToken));
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
        $this->passwordSecure = true;
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function generatePassword(string $plainPassword, string $password): void
    {
        $this->passwordSecure = false;
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();

        $this->raise(
            new AdminPasswordChangedEvent(
                $this->uuid,
                $this->email,
                $this->firstname . " " . $this->lastname,
                $plainPassword
            )
        );
    }

    public function activate(): void
    {
        if ($this->status === Status::ACTIVATED) {
            throw new DomainException(new TranslatableMessage("Admin is already activated.", [], "domain"));
        }

        if ($this->status === Status::BLOCKED) {
            throw new DomainException(new TranslatableMessage("Admin is blocked.", [], "domain"));
        }

        $this->status = Status::ACTIVATED;
        $this->confirmationToken = null;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function block(): void
    {
        if ($this->status === Status::BLOCKED) {
            throw new DomainException(new TranslatableMessage("Admin is already blocked.", [], "domain"));
        }

        if ($this->role->value === Role::ROLE_SUPER_ADMIN->value) {
            throw new DomainException(
                new TranslatableMessage("It is not possible to block the superadmin.", [], "domain")
            );
        }

        $this->status = Status::BLOCKED;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function unblock(): void
    {
        if ($this->status !== Status::BLOCKED) {
            throw new DomainException(new TranslatableMessage("Admin is not blocked.", [], "domain"));
        }

        $this->status = $this->confirmationToken === null ? Status::ACTIVATED : Status::DISABLED;

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
