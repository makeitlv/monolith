<?php

declare(strict_types=1);

namespace App\Admin\Domain\Service;

interface PasswordEncoder
{
    public function encode(string $plainPassword): string;
}
