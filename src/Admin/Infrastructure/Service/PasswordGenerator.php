<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Domain\Service\PasswordGeneratorInterface;

final class PasswordGenerator implements PasswordGeneratorInterface
{
    private const BYTES_LENGTH = 32;
    private const PASSWORD_LENGTH = 8;

    public function __construct(private PasswordEncoder $passwordEncoder)
    {
    }

    public function generate(): string
    {
        return substr(
            preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(bin2hex(random_bytes(self::BYTES_LENGTH)))),
            0,
            self::PASSWORD_LENGTH
        );
    }
}
