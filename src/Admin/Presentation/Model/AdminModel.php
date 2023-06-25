<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity]
#[ORM\Table("admin")]
class AdminModel
{
    #[ORM\Id]
    #[ORM\Column]
    public string $uuid;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    #[Assert\Email]
    public string $email;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 64)]
    public string $firstname;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 64)]
    public string $lastname;

    #[ORM\Column]
    public string $role;

    #[ORM\Column]
    public string $status;

    #[ORM\Column]
    public string $password;

    public ?string $currentPassword = null;

    #[Assert\Length(min: 5)]
    #[Assert\PasswordStrength]
    #[Assert\NotCompromisedPassword]
    public ?string $newPassword = null;

    #[ORM\Column]
    public string $confirmationToken;

    #[ORM\Column]
    public DateTimeImmutable $createdAt;

    #[ORM\Column]
    public DateTimeImmutable $updatedAt;

    public function getRole(): array
    {
        return [$this->role];
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
