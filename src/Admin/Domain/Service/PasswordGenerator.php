<?php

declare(strict_types=1);

namespace App\Admin\Domain\Service;

interface PasswordGenerator
{
    public function generate(): string;
}
