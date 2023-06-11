<?php

declare(strict_types=1);

namespace App\Admin\Domain\Query;

use App\Admin\Domain\DataTransfer\AdminDTO;

interface AdminQueryInterface
{
    public function findByEmail(string $email): ?AdminDTO;
    public function findByConfirmationToken(string $confirmationToken): ?AdminDTO;
}
