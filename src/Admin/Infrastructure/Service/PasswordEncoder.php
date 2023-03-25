<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Domain\Service\PasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class PasswordEncoder implements PasswordEncoderInterface
{
    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }

    public function encode(string $plainPassword): string
    {
        return $this->passwordHasherFactory
            ->getPasswordHasher(PasswordAuthenticatedUserInterface::class)
            ->hash($plainPassword);
    }
}
