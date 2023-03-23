<?php

declare(strict_types=1);

namespace App\Admin\Domain\Service;

interface ConfirmationTokenGeneratorInterface
{
    public function generate(): string;
}
