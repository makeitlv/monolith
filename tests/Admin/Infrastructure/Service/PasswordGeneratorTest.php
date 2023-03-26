<?php

declare(strict_types=1);

namespace App\Test\Admin\Infrastructure\Service;

use App\Admin\Infrastructure\Service\PasswordGenerator;
use PHPUnit\Framework\TestCase;

final class PasswordGeneratorTest extends TestCase
{
    public function testGenerator(): void
    {
        $password = (new PasswordGenerator())->generate();

        $this->assertEquals(8, strlen($password));
    }
}
