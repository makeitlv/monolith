<?php

declare(strict_types=1);

namespace App\Admin\Domain\Service;

interface PasswordGeneratorInterface
{
    public function generate(): string;
}
