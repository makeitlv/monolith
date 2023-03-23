<?php

declare(strict_types=1);

namespace App\Admin\Domain\Service;

interface PasswordEncoderInterface
{
    public function encode(string $plainPassword): string;
}
