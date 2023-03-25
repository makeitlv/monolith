<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Service;

use App\Admin\Domain\Service\ConfirmationTokenGeneratorInterface;

final class ConfirmationTokenGenerator implements ConfirmationTokenGeneratorInterface
{
    private const LENGTH = 16;

    public function generate(): string
    {
        return bin2hex(random_bytes(self::LENGTH));
    }
}
