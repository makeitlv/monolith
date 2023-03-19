<?php

declare(strict_types=1);

namespace App\Admin\Domain\Service;

interface ConfirmationTokenGenerator
{
    public function generate(): string;
}
