<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Api\Exception;

use Exception;

final class AdminNotFoundException extends Exception
{
    public function __construct(string $message = "Admin not found.", int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
