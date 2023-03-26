<?php

declare(strict_types=1);

namespace App\Test\Admin\Infrastructure\Service;

use App\Admin\Infrastructure\Service\ConfirmationTokenGenerator;
use PHPUnit\Framework\TestCase;

final class ConfirmationTokenGeneratorTest extends TestCase
{
    public function testGenerator(): void
    {
        $token = (new ConfirmationTokenGenerator())->generate();

        $this->assertEquals(32, strlen($token));
    }
}
